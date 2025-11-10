<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Book;
use App\Models\Chapter;

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

        require_once ROOT_PATH . '/app/Views/books/show.php';
    }

    public function show()
    {
        return $this->detail();
    }
}