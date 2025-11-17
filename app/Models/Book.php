<?php
// File: app/Models/Book.php
namespace App\Models;
use PDO;

class Book {
    private $conn;
    private $table = "Books";

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    // Lấy tất cả sách
    public function getAllBooks() {
        $query = "SELECT book_id, title, author, cover_image FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm sách bằng ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE book_id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy sách theo tên tác giả
    public function findByAuthorName($authorName) {
        $query = "SELECT * FROM " . $this->table . " WHERE author = :author_name ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_name', $authorName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    /**
     * Tạo một sách mới
     */
    public function create($data) {
        $sql = "INSERT INTO Books (title, author, category_id, description, cover_image) 
                VALUES (:title, :author, :category_id, :description, :cover_image)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':author' => $data['author'],
            ':category_id' => $data['category_id'],
            ':description' => $data['description'],
            ':cover_image' => $data['cover_image']
        ]);
        
        return $this->conn->lastInsertId();
    }

    // =================================================================
    // ✅ HÀM UPDATE() BẠN ĐANG THIẾU NẰM Ở ĐÂY
    // =================================================================

    /**
     * Cập nhật thông tin chi tiết của một cuốn sách (cho Admin)
     */
    public function update($bookId, $data) {
        $sql = "UPDATE Books 
                SET title = :title, 
                    description = :description, 
                    category_id = :category_id 
                WHERE book_id = :book_id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':category_id' => $data['category_id'],
            ':book_id' => $bookId
        ]);
    }

    /**
     * Xóa một cuốn sách (cho Admin)
     */
    public function delete($bookId) {
        $sql = "DELETE FROM Books WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':book_id' => $bookId]);
    }
}