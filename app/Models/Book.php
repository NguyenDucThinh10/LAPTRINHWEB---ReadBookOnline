<?php
// File: app/Models/Book.php

class Book {
    private $conn;
    private $table = "Books";

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    // Lấy tất cả sách với các thông tin cần thiết
    public function getAllBooks() {
        // Lấy đúng các cột: book_id, title, author, và quan trọng là 'cover_image'
        $query = "SELECT book_id, title, author, cover_image FROM " . $this->table . " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE book_id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Thêm kiểu dữ liệu để an toàn hơn
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}