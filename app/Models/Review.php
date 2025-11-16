<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Review
{
    private $conn;
    private $table = 'Reviews';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function listByBook(int $bookId, int $offset = 0, int $limit = 5): array
    {
        $sql = "SELECT r.review_id, r.user_id, r.book_id, r.rating, r.comment, r.created_at,
                       u.username
                FROM {$this->table} r
                JOIN Users u ON u.user_id = r.user_id
                WHERE r.book_id = :bid
                ORDER BY r.created_at DESC
                LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':bid', $bookId, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countByBook(int $bookId): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE book_id = :bid");
        $stmt->execute([':bid' => $bookId]);
        return (int)$stmt->fetchColumn();
    }

    public function avgByBook(int $bookId): float
    {
        $stmt = $this->conn->prepare("SELECT AVG(rating) FROM {$this->table} WHERE book_id = :bid");
        $stmt->execute([':bid' => $bookId]);
        $avg = $stmt->fetchColumn();
        return $avg ? round((float)$avg, 1) : 0.0;
    }

    public function findByBookAndUser(int $bookId, int $userId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE book_id = :bid AND user_id = :uid LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':bid' => $bookId, ':uid' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function add(int $bookId, int $userId, int $rating, ?string $comment): bool
    {
        $sql = "INSERT INTO {$this->table} (book_id, user_id, rating, comment)
                VALUES (:bid, :uid, :rating, :comment)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':bid'     => $bookId,
            ':uid'     => $userId,
            ':rating'  => $rating,
            ':comment' => $comment
        ]);
    }

    public function updateMy(int $bookId, int $userId, int $rating, ?string $comment): bool
    {
        $sql = "UPDATE {$this->table}
                SET rating = :rating, comment = :comment
                WHERE book_id = :bid AND user_id = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':rating'  => $rating,
            ':comment' => $comment,
            ':bid'     => $bookId,
            ':uid'     => $userId
        ]);
        return $stmt->rowCount() > 0;
    }

    public function deleteMy(int $bookId, int $userId): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE book_id = :bid AND user_id = :uid"
        );
        $stmt->execute([':bid' => $bookId, ':uid' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
