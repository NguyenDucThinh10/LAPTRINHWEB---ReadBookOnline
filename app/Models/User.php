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

    // ✅ Kiểm tra email đã tồn tại chưa
    public static function emailExists($email)
    {
        return self::findByEmail($email) !== false;
    }

    // ✅ Tìm user theo username (có thể dùng thêm)
    public static function findByUsername($username)
    {
        $db = static::getConnection();
        $sql = "SELECT * FROM Users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Kiểm tra username đã tồn tại chưa
    public static function usernameExists($username)
    {
        return self::findByUsername($username) !== false;
    }
}