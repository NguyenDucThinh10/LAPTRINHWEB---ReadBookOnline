<?php
// File: app/Controllers/BookController.php

require_once '../app/Core/Database.php';
require_once '../app/Models/Book.php';
require_once '../app/Models/Chapter.php'; // Thêm dòng này

class BookController {
    
    public function detail() {
        $bookId = $_GET['id'] ?? null;

        if (!$bookId) {
            die("Thiếu ID sách!");
        }

        $database = new Database();
        $db = $database->getConnection();
        
        // Lấy thông tin sách
        $bookModel = new Book($db);
        $book = $bookModel->findById($bookId);

        // Lấy danh sách chương
        $chapterModel = new Chapter($db);
        $chapters = $chapterModel->getChaptersByBookId($bookId);

        // Gọi view và truyền cả $book và $chapters
        require_once '../app/Views/books/show.php';
    }
}