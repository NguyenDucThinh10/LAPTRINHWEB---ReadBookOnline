<?php
// File: app/Controllers/HomeController.php

require_once '../app/Core/Database.php';
require_once '../app/Models/Book.php';

class HomeController {
    public function index() {
        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        
        // DÒNG NÀY TẠO RA BIẾN $books
        $books = $bookModel->getAllBooks(); 

        // Sau đó nó gọi phòng trưng bày và mang theo biến $books
        require_once '../app/Views/home.php';
    }
}