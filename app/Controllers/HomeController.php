<?php
namespace App\Controllers;

use App\Core\Controller; // <-- Thêm dòng này
use App\Core\Database;
use App\Models\Book;

class HomeController extends Controller // <-- Kế thừa từ Controller
{ 
    public function index() {
        $db = Database::getConnection();
        $bookModel = new Book($db);
        $books = $bookModel->getAllBooks();

        // Sử dụng $this->view() để truyền dữ liệu một cách tường minh
        return $this->view('home', [
            'books' => $books
        ]);
    }
}