<?php
// File: app/Models/Chapter.php
namespace App\Models;
use PDO;
class Chapter {
    private $conn;
    private $table = "Chapters";

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    // Lấy tất cả các chương của một cuốn sách (bạn đã có)
    public function getChaptersByBookId($book_id) {
        $query = "SELECT chapter_id, chapter_number, title 
                  FROM " . $this->table . " 
                  WHERE book_id = :book_id 
                  ORDER BY chapter_number ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === MÓN MỚI 1: Lấy thông tin một chương cụ thể bằng ID ===
    public function findById($chapter_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE chapter_id = :chapter_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === MÓN MỚI 2: Tìm chương kế tiếp ===
    public function findNextChapter($book_id, $current_chapter_number) {
        $query = "SELECT chapter_id FROM " . $this->table . " 
                  WHERE book_id = :book_id AND chapter_number > :current_chapter_number 
                  ORDER BY chapter_number ASC 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':current_chapter_number', $current_chapter_number);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === MÓN MỚI 3: Tìm chương trước đó ===
    public function findPreviousChapter($book_id, $current_chapter_number) {
        $query = "SELECT chapter_id FROM " . $this->table . " 
                  WHERE book_id = :book_id AND chapter_number < :current_chapter_number 
                  ORDER BY chapter_number DESC 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':current_chapter_number', $current_chapter_number);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}