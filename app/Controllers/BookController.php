<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Book;
use App\Models\Chapter;

class BookController
{
    public function detail($id)
    {
        $bookId = (int)$id;
        // $bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
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

    public function search() {
        // Lấy từ khóa tìm kiếm từ URL (?q=...)
        $keyword = $_GET['q'] ?? '';

        $db = Database::getConnection();
        $bookModel = new Book($db);
        
        // Gọi model để tìm kiếm
        $books = $bookModel->searchByTitle($keyword);

        // Gọi một file view mới để hiển thị kết quả
        require_once ROOT_PATH . '/app/Views/books/search_results.php';
    }
    public function listByCategory($id) {
        $categoryId = (int)$id;
        if (!$categoryId) {
            die("Thiếu ID thể loại.");
        }

        $db = Database::getConnection();
        $bookModel = new Book($db);
        // Lấy danh sách sách thuộc thể loại này
        $books = $bookModel->findByCategoryId($categoryId);
        
        // Lấy thông tin của thể loại để hiển thị tên (bonus)
        // (Bạn cần có phương thức findById trong Category Model)
        // $categoryModel = new Category($db);
        // $category = $categoryModel->findById($categoryId);

        // Tái sử dụng view hiển thị kết quả tìm kiếm!
        $keyword = "Thể loại"; // Gán một giá trị tạm
        require_once ROOT_PATH . '/app/Views/books/search_results.php';
    }
}