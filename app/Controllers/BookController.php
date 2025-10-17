<?php
// File: app/Controllers/BookController.php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Book;
use App\Models\Chapter;

class BookController {
    
    public function detail() {
        $bookId = $_GET['id'] ?? null;

        if (!$bookId) {
            die("Thiếu ID sách!");
        }

        // CÁCH KẾT NỐI MỚI - GỌN HƠN VÀ HIỆU QUẢ HƠN
        $db = Database::getConnection();
        
        // Phần còn lại giữ nguyên
        $bookModel = new Book($db);
        $book = $bookModel->findById($bookId);

        $chapterModel = new Chapter($db);
        $chapters = $chapterModel->getChaptersByBookId($bookId);

        require_once '../app/Views/books/show.php';
    }
}