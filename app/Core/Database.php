<?php
// app/Core/Database.php

class Database {
    private $host;
    private $dbname;
    private $user;
    private $password;
    private $conn;

    public function __construct() {
        // Tải cấu hình từ file
        $config = require_once '../config/database.php';
        
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->user = $config['user'];
        $this->password = $config['password'];
    }

    public function getConnection() {
        $this->conn = null;
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
        
        try {
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Lỗi kết nối CSDL: " . $e->getMessage();
            exit; // Dừng chương trình nếu không kết nối được
        }

        return $this->conn;
    }
}