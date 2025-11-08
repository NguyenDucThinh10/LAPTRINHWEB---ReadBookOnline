<?php
namespace App\Models;
use PDO;
class Chapter {
    private $conn;
    private $table = "Chapters";

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    // =================================================================
    // ✅ HÀM MỚI ĐƯỢC THÊM VÀO
    // =================================================================
    /**
     * Tạo một chương mới (Dùng cho chức năng Thêm sách)
     * @param array $data Dữ liệu chương (book_id, chapter_number, title, content)
     * @return bool
     */
    public function create($data) {
        $sql = "INSERT INTO Chapters (book_id, chapter_number, title, content) 
                VALUES (:book_id, :chapter_number, :title, :content)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':book_id' => $data['book_id'],
            ':chapter_number' => $data['chapter_number'],
            ':title' => $data['title'],
            ':content' => $data['content']
        ]);
    }

    // =================================================================
    // CÁC HÀM CŨ CỦA BẠN (GIỮ NGUYÊN)
    // =================================================================

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

    // Lấy thông tin một chương cụ thể bằng ID
    public function findById($chapter_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE chapter_id = :chapter_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm chương kế tiếp
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

    // Tìm chương trước đó
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