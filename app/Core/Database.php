<?php
// app/Core/Database.php

namespace App\Core; // Giữ lại namespace, đây là một thực hành tốt

use PDO;
use PDOException;

class Database
{
    // Dùng static để đảm bảo chỉ có 1 kết nối duy nhất (Singleton Pattern)
    protected static $connection;

    // Phương thức static, gọi trực tiếp mà không cần tạo đối tượng: Database::getConnection()
    public static function getConnection()
    {
        if (!self::$connection) {
            $config = require_once __DIR__ . '/../../config/database.php';
            try {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
self::$connection = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Database connection failed: '. $e->getMessage());
            }
        }
        return self::$connection;
    }
}