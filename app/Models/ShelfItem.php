<?php
namespace App\Models;

use App\Core\Database;   // ← dùng đúng namespace bạn đang có
use PDO;

class ShelfItem {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection(); // ← đúng method của bạn
    }

    public function exists(int $userId, int $bookId): bool {
        $st = $this->db->prepare(
            "SELECT 1 FROM ShelfItems WHERE user_id = ? AND book_id = ? LIMIT 1"
        );
        $st->execute([$userId, $bookId]);
        return (bool) $st->fetchColumn();
    }

    public function add(int $userId, int $bookId, string $status = 'want_to_read'): bool {
        $st = $this->db->prepare(
            "INSERT INTO ShelfItems (user_id, book_id, status) VALUES (?, ?, ?)"
        );
        return $st->execute([$userId, $bookId, $status]);
    }

    public function updateStatus(int $userId, int $bookId, string $status): bool {
        $st = $this->db->prepare(
            "UPDATE ShelfItems SET status = ? WHERE user_id = ? AND book_id = ?"
        );
        return $st->execute([$status, $userId, $bookId]);
    }

    public function remove(int $userId, int $bookId): bool {
        $st = $this->db->prepare(
            "DELETE FROM ShelfItems WHERE user_id = ? AND book_id = ?"
        );
        return $st->execute([$userId, $bookId]);
    }

    public function listByUser(int $userId, ?string $status = null): array {
        if ($status) {
            $sql = "SELECT s.*, b.title, b.author, b.cover_image, b.book_id
                    FROM ShelfItems s
                    JOIN Books b ON b.book_id = s.book_id
                    WHERE s.user_id = ? AND s.status = ?
                    ORDER BY s.updated_at DESC";
            $st = $this->db->prepare($sql);
            $st->execute([$userId, $status]);
        } else {
            $sql = "SELECT s.*, b.title, b.author, b.cover_image, b.book_id
                    FROM ShelfItems s
                    JOIN Books b ON b.book_id = s.book_id
                    WHERE s.user_id = ?
                    ORDER BY s.updated_at DESC";
            $st = $this->db->prepare($sql);
            $st->execute([$userId]);
        }
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
