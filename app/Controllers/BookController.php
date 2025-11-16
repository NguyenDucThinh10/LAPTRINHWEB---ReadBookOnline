<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Review;

class BookController
{
    public function detail()
    {
        $bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($bookId <= 0) {
            http_response_code(400);
            exit("Thiếu hoặc sai ID sách!");
        }

        $db = Database::getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->findById($bookId);

        if (!$book) {
            http_response_code(404);
            exit("Không tìm thấy sách!");
        }

        $chapterModel = new Chapter($db);
        $chapters = $chapterModel->getChaptersByBookId($bookId);
        
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 5;
        $offset = ($page - 1) * $limit;

        $rvModel = new Review();
        $reviews = $rvModel->listByBook($bookId, $offset, $limit);
        $total   = $rvModel->countByBook($bookId);
        $avg     = $rvModel->avgByBook($bookId);

        if (session_status()===PHP_SESSION_NONE) session_start();
        $myReview = !empty($_SESSION['user_id'])
            ? $rvModel->findByBookAndUser($bookId, (int)$_SESSION['user_id'])
            : null;

        require_once ROOT_PATH . '/app/Views/books/show.php';
    }

    public function show()
    {
        return $this->detail();
    }
}