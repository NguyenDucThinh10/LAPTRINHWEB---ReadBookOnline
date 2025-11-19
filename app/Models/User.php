<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User extends Database
{
    protected static $table = 'Users';

    // Tạo user mới
    public static function create($data)
    {
        $db = static::getConnection();
        $sql = "INSERT INTO Users (username, email, password, role, created_at)
                VALUES (:username, :email, :password, 'reader', NOW())";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':username' => $data['username'],
            ':email'    => $data['email'],
            ':password' => $data['password']
        ]);
    }
    
    /**
     * Tìm một người dùng bằng ID.
     */
    public static function findById($id)
    {
        $db = static::getConnection();
        $sql = "SELECT user_id, username, email, role, created_at, author_name 
                FROM Users WHERE user_id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Cập nhật thông tin người dùng (ví dụ: email).
     */
    public static function update($id, $data)
    {
        $db = static::getConnection();
        $sql = "UPDATE Users SET email = :email WHERE user_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':email' => $data['email'],
            ':id'    => $id
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Cập nhật mật khẩu mới cho người dùng.
     */
    public static function updatePassword($id, $newPassword)
    {
        $db = static::getConnection();
        $sql = "UPDATE Users SET password = :password WHERE user_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':password' => $newPassword,
            ':id'       => $id
        ]);
        return $stmt->rowCount() > 0;
    }

    // =================================================================
    // CÁC HÀM CHO CHỨC NĂNG ADMIN
    // =================================================================

    /**
     * Nâng cấp vai trò của user thành 'author' và lưu bút danh
     */
    public static function upgradeToAuthor($userId, $authorName)
    {
        $db = static::getConnection();
        $sql = "UPDATE Users SET role = 'author', author_name = :author_name 
                WHERE user_id = :user_id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':author_name' => $authorName,
            ':user_id'     => $userId
        ]);
    }
    
    /**
     * Lấy tất cả người dùng (cho Admin)
     */
    public static function getAll()
    {
        $db = static::getConnection();
        $sql = "SELECT user_id, username, email, role, author_name, created_at 
                FROM Users ORDER BY created_at DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật vai trò (role) của một người dùng
     */
    public static function updateRole($userId, $newRole)
    {
        $db = static::getConnection();
        $sql = "UPDATE Users SET role = :role WHERE user_id = :user_id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':role' => $newRole,
            ':user_id' => $userId
        ]);
    }

    /**
     * ✅ HÀM MỚI BẠN CÒN THIẾU
     * Xóa một người dùng khỏi cơ sở dữ liệu
     */
    public static function delete($userId)
    {
        $db = static::getConnection();
        $sql = "DELETE FROM Users WHERE user_id = :user_id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    // =================================================================
    // CÁC HÀM TÌM KIẾM VÀ XÁC THỰC
    // =================================================================

    // Tìm user theo username hoặc email (dùng cho đăng nhập)
    public static function findByUsernameOrEmail($username, $email)
    {
        $db = static::getConnection();
        $sql = "SELECT * FROM Users WHERE username = :username OR email = :email LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email'    => $email
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm user theo email (dùng cho kiểm tra email trùng)
    public static function findByEmail($email)
    {
        $db = static::getConnection();
        $sql = "SELECT * FROM Users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra email đã tồn tại chưa
    public static function emailExists($email)
    {
        return self::findByEmail($email) !== false;
    }

    // Tìm user theo username
    public static function findByUsername($username)
    {
        $db = static::getConnection();
        $sql = "SELECT * FROM Users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra username đã tồn tại chưa
    public static function usernameExists($username)
    {
        return self::findByUsername($username) !== false;
    }

    // Lấy user (bao gồm cả password hash) để xác thực
    public static function findWithPasswordById($id)
    {
        $db = static::getConnection();
        $sql = "SELECT * FROM Users WHERE user_id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // ... bên trong class User
    public static function countAll() {
        $db = static::getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM Users");
        return $stmt->fetchColumn();
    }
}