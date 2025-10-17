<?php
namespace App\Controllers;
// File: app/Controllers/HomeController.php
use App\Core\Database;
use App\Models\Book;

class HomeController {
    
    public function index() {
        // CÁCH KẾT NỐI MỚI - GỌN HƠN VÀ HIỆU QUẢ HƠN
        $db = Database::getConnection();

        // Phần còn lại giữ nguyên
        $bookModel = new Book($db);
        $books = $bookModel->getAllBooks(); 

        require_once '../app/Views/home.php';
    }
}