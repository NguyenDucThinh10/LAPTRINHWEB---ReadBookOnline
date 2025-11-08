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
     * Lấy tất cả thể loại
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}