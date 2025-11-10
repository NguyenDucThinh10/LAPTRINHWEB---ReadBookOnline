<?php
namespace App\Controllers;

use App\Models\ShelfItem;

class ShelfController {
    private ShelfItem $shelf;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

      // ✅ FAKE LOGIN TẠM (xóa khối này khi nhóm login xong)
if (empty($_SESSION['user'])) {
    $_SESSION['user'] = [
        'user_id'  => 1,
        'username' => 'demo_user'
    ];
}



        $this->shelf = new ShelfItem();
    }

    // 1) Thêm sách vào tủ
    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); exit('Method Not Allowed');
        }

        if (empty($_SESSION['user'])) {
           $this->redirect('/auth/login', 'Bạn cần đăng nhập để thêm sách vào tủ.');

        }

        $userId = (int) ($_SESSION['user']['user_id'] ?? 0);
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $status = $_POST['status'] ?? 'want_to_read';

        if ($bookId <= 0) $this->back('Thiếu hoặc sai book_id.');

        if ($this->shelf->exists($userId, $bookId)) {
            $this->back('Sách đã có trong tủ của bạn.');
        }

        $ok = $this->shelf->add($userId, $bookId, $status);
        $this->back($ok ? 'Đã thêm vào tủ thành công!' : 'Thêm thất bại.');
    }

    // 2) Cập nhật trạng thái
    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); exit('Method Not Allowed');
        }

        if (empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/?controller=auth&action=login', 'Bạn cần đăng nhập.');
        }

        $userId = (int) ($_SESSION['user']['user_id'] ?? 0);
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $status = $_POST['status'] ?? 'want_to_read';

        $ok = $this->shelf->updateStatus($userId, $bookId, $status);
        $this->back($ok ? 'Đã cập nhật trạng thái.' : 'Cập nhật thất bại.');
    }

    // 3) Xóa sách khỏi tủ
    public function remove(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); exit('Method Not Allowed');
        }

        if (empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/?controller=auth&action=login', 'Bạn cần đăng nhập.');
        }

        $userId = (int) ($_SESSION['user']['user_id'] ?? 0);
        $bookId = (int) ($_POST['book_id'] ?? 0);

        $ok = $this->shelf->remove($userId, $bookId);
        $this->back($ok ? 'Đã xóa khỏi tủ.' : 'Xóa thất bại.');
    }

    // 4) Hiển thị danh sách tủ
    public function index(): void {
        if (empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/?controller=auth&action=login', 'Bạn cần đăng nhập.');
        }

        $userId = (int) ($_SESSION['user']['user_id'] ?? 0);
        $status = $_GET['status'] ?? null;
        $items  = $this->shelf->listByUser($userId, $status);

        include __DIR__ . '/../Views/shelf/index.php';
    }

    // ===== Helpers =====
    private function back(string $msg): void {
    $_SESSION['flash'] = $msg;
    $fallback = BASE_URL . '/shelf'; // ✅ Router mới dùng clean URL
    $goto = $_SERVER['HTTP_REFERER'] ?? $fallback;
    header('Location: ' . $goto);
    exit;
}

private function redirect(string $path, string $msg): void {
    $_SESSION['flash'] = $msg;
    // ✅ Nếu $path là path tương đối, gắn BASE_URL
    if (strpos($path, 'http://') !== 0 && strpos($path, 'https://') !== 0) {
        $path = BASE_URL . $path;
    }
    header('Location: ' . $path);
    exit;
}

}
