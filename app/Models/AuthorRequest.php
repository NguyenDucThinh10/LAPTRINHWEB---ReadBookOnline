<?php
// File: app/Models/AuthorRequest.php

namespace App\Models;

use App\Core\Database;
use PDO;

class AuthorRequest extends Database
{
    /**
     * Tạo một yêu cầu đăng ký tác giả mới
     */
    public static function create($userId, $penName)
    {
        $db = static::getConnection();
        // Trạng thái mặc định là 'pending'
        $sql = "INSERT INTO AuthorRequests (user_id, author_name, status) 
                VALUES (:user_id, :author_name, 'pending')";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':user_id'     => $userId,
            ':author_name' => $penName
        ]);
    }

    /**
     * Tìm một yêu cầu dựa trên ID của người dùng (user_id)
     */
    public static function findByUserId($userId)
    {
        $db = static::getConnection();
        $sql = "SELECT * FROM AuthorRequests WHERE user_id = :user_id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tất cả các yêu cầu đang chờ (pending) để Admin duyệt
     * Chúng ta JOIN với bảng Users để lấy tên và email của người yêu cầu
     */
    public static function getPendingRequests()
    {
        $db = static::getConnection();
        $sql = "SELECT ar.*, u.username, u.email 
                FROM AuthorRequests ar
                JOIN Users u ON ar.user_id = u.user_id
                WHERE ar.status = 'pending'
                ORDER BY ar.created_at ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Cập nhật trạng thái của một yêu cầu (ví dụ: 'approved' hoặc 'rejected')
     */
    public static function updateStatus($requestId, $status)
    {
        $db = static::getConnection();
        $sql = "UPDATE AuthorRequests SET status = :status WHERE request_id = :request_id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':request_id' => $requestId
        ]);
    }
    // ... bên trong class AuthorRequest
    public static function countPending() {
        $db = static::getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM AuthorRequests WHERE status = 'pending'");
        return $stmt->fetchColumn();
    }
}