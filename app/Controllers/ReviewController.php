<?php
// File: app/Controllers/ReviewController.php
// (PHIÊN BẢN ĐÃ SỬA LỖI)

namespace App\Controllers;

use App\Models\Review;

class ReviewController
{
    /**
     * ✅ ĐÃ SỬA: Chuyển hướng đến route /auth/login "đẹp"
     */
    private function requireLogin(): int
    {
        if (session_status()===PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) {
            $next = $_SERVER['HTTP_REFERER'] ?? '/';
            // Sửa: Xóa BASE_URL và /public/
            header('Location: /auth/login?next=' . urlencode($next));
            exit;
        }
        return (int)$_SESSION['user_id'];
    }


    public function add()
    {
        if (session_status()===PHP_SESSION_NONE) session_start();
        $uid = $this->requireLogin();

        $bookId  = (int)($_POST['book_id'] ?? 0);
        $rating  = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($bookId<=0 || $rating<1 || $rating>5) {
            $_SESSION['flash'] = 'Dữ liệu không hợp lệ!';
            // ✅ ĐÃ SỬA: Chuyển hướng đến route "đẹp"
            header('Location: /book/show?id=' . $bookId . '#reviews');
            exit;
        }

        $rv   = new Review();
        
        // ✅ ĐÃ SỬA: Xóa logic "if ($mine)" thừa thãi.
        // View (show.php) đã gọi đúng hàm "add", nên chúng ta chỉ cần "add".
        $rv->add($bookId, $uid, $rating, $comment);

        $_SESSION['flash'] = 'Đã lưu đánh giá.';
        // ✅ ĐÃ SỬA: Chuyển hướng đến route "đẹp"
        header('Location: /book/show?id=' . $bookId . '#reviews');
        exit;
    }

    public function update()
    {
        if (session_status()===PHP_SESSION_NONE) session_start();
        $uid = $this->requireLogin();

        $bookId  = (int)($_POST['book_id'] ?? 0);
        $rating  = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($bookId<=0 || $rating<1 || $rating>5) {
            $_SESSION['flash'] = 'Dữ liệu không hợp lệ!';
            // ✅ ĐÃ SỬA: Chuyển hướng đến route "đẹp"
            header('Location: /book/show?id=' . $bookId . '#reviews');
            exit;
        }

        $rv = new Review();
        $rv->updateMy($bookId, $uid, $rating, $comment);

        $_SESSION['flash'] = 'Đã cập nhật đánh giá.';
        // ✅ ĐÃ SỬA: Chuyển hướng đến route "đẹp"
        header('Location: /book/show?id=' . $bookId . '#reviews');
        exit;
    }

    public function delete()
    {
        if (session_status()===PHP_SESSION_NONE) session_start();
        $uid = $this->requireLogin();

        $bookId = (int)($_POST['book_id'] ?? 0);
        if ($bookId<=0) {
            $_SESSION['flash'] = 'Thiếu ID sách!';
            // ✅ ĐÃ SỬA: Chuyển hướng đến trang chủ
            header('Location: /');
            exit;
        }

        $rv = new Review();
        $rv->deleteMy($bookId, $uid);

        $_SESSION['flash'] = 'Đã xóa đánh giá.';
        // ✅ ĐÃ SỬA: Chuyển hướng đến route "đẹp"
        header('Location: /book/show?id=' . $bookId . '#reviews');
        exit;
    }
}