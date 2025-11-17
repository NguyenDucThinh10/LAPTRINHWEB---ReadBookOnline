<?php
namespace App\Controllers;

use App\Models\Review;

class ReviewController
{
    private function requireLogin(): int
    {
        if (session_status()===PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) {
            $next = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/public/');
            // thêm /public vào sau BASE_URL
            header('Location: ' . BASE_URL . '/public/auth/login?next=' . urlencode($next));
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
            header('Location: ' . BASE_URL . '/public/book/detail?id=' . $bookId . '#reviews');
            exit;
        }

        $rv   = new Review();
        $mine = $rv->findByBookAndUser($bookId, $uid);

        if ($mine) {
            $rv->updateMy($bookId, $uid, $rating, $comment);
        } else {
            $rv->add($bookId, $uid, $rating, $comment);
        }

        $_SESSION['flash'] = 'Đã lưu đánh giá.';
        header('Location: ' . BASE_URL . '/public/book/detail?id=' . $bookId . '#reviews');
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
            header('Location: ' . BASE_URL . '/public/book/detail?id=' . $bookId . '#reviews');
            exit;
        }

        $rv = new Review();
        $rv->updateMy($bookId, $uid, $rating, $comment);

        $_SESSION['flash'] = 'Đã cập nhật đánh giá.';
        header('Location: ' . BASE_URL . '/public/book/detail?id=' . $bookId . '#reviews');
        exit;
    }

    public function delete()
    {
        if (session_status()===PHP_SESSION_NONE) session_start();
        $uid = $this->requireLogin();

        $bookId = (int)($_POST['book_id'] ?? 0);
        if ($bookId<=0) {
            $_SESSION['flash'] = 'Thiếu ID sách!';
            header('Location: ' . BASE_URL . '/public/');
            exit;
        }

        $rv = new Review();
        $rv->deleteMy($bookId, $uid);

        $_SESSION['flash'] = 'Đã xóa đánh giá.';
        header('Location: ' . BASE_URL . '/public/book/detail?id=' . $bookId . '#reviews');
        exit;
    }
}
