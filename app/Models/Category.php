<?php
// File: app/Models/Category.php
namespace App\Models;
use PDO;

class Category {
    private $conn;
    private $table = "Category";

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Lấy tất cả thể loại (Hàm bạn đã có)
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * Tìm một thể loại bằng ID
     */
    public function findById($categoryId) {
        $query = "SELECT * FROM " . $this->table . " WHERE category_id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo một thể loại mới
     */
    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description']
        ]);
    }

    /**
     * Cập nhật một thể loại
     */
    public function update($categoryId, $data) {
        $sql = "UPDATE " . $this->table . " SET name = :name, description = :description 
                WHERE category_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':id' => $categoryId
        ]);
    }

    /**
     * Xóa một thể loại
     */
    public function delete($categoryId) {
        $sql = "DELETE FROM " . $this->table . " WHERE category_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $categoryId]);
    }
    public function getList($limit = 6) {
        $query = "SELECT category_id, name FROM " . $this->table . " ORDER BY name ASC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    // ... bên trong class Category
    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table);
        return $stmt->fetchColumn();
    }
}