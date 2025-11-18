<?php
// File: app/Controllers/ShelfController.php

namespace App\Controllers;

// ❌ KHÔNG "extends Controller"
// ❌ KHÔNG "use App\Core\Controller"
use App\Models\ShelfItem;

class ShelfController { // ⬅️ Đã xóa "extends Controller"
    private ShelfItem $shelf;
    private int $userId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đăng nhập (giữ nguyên)
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/auth/login', 'Bạn cần đăng nhập để xem tủ sách.');
        }
        
        $this->userId = (int) $_SESSION['user_id'];
        $this->shelf = new ShelfItem();
    }

    // 1) Thêm sách vào tủ
    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); exit('Method Not Allowed');
        }
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $status = $_POST['status'] ?? 'want_to_read';
        if ($bookId <= 0) $this->back('Thiếu hoặc sai book_id.');
        if ($this->shelf->exists($this->userId, $bookId)) {
            $this->back('Sách đã có trong tủ của bạn.');
        }
        $ok = $this->shelf->add($this->userId, $bookId, $status);
        $this->back($ok ? 'Đã thêm vào tủ thành công!' : 'Thêm thất bại.');
    }

    // 2) Cập nhật trạng thái
    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); exit('Method Not Allowed');
        }
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $status = $_POST['status'] ?? 'want_to_read';
        $ok = $this->shelf->updateStatus($this->userId, $bookId, $status);
        $this->back($ok ? 'Đã cập nhật trạng thái.' : 'Cập nhật thất bại.');
    }

    // 3) Xóa sách khỏi tủ
    public function remove(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); exit('Method Not Allowed');
        }
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $ok = $this->shelf->remove($this->userId, $bookId);
        $this->back($ok ? 'Đã xóa khỏi tủ.' : 'Xóa thất bại.');
    }


    // 4) Hiển thị danh sách tủ
    public function index(): void {
        $status = $_GET['status'] ?? null;
        $items  = $this->shelf->listByUser($this->userId, $status);
        
        // (Biến $items sẽ tự động có sẵn trong file view)

        // ✅ QUAY LẠI CÁCH CŨ: Dùng "require"
        // (File index.php sẽ tự lo nạp layout)
        require __DIR__ . '/../Views/shelf/index.php';
    }

    // ===== Helpers (Giữ nguyên) =====
    
    private function back(string $msg): void {
        $_SESSION['flash'] = $msg;
        $fallback = '/shelf'; 
        $goto = $_SERVER['HTTP_REFERER'] ?? $fallback;
        header('Location: ' . $goto);
        exit;
    }

    private function redirect(string $path, string $msg): void {
        $_SESSION['flash'] = $msg;
        $url = '/' . ltrim($path, '/');
        header('Location: ' . $url);
        exit;
    }
}