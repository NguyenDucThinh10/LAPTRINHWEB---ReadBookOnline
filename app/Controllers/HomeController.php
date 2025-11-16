<?php
namespace App\Controllers;

use App\Core\Controller; 
use App\Core\Database;
use App\Models\Book;
// Nếu chưa có, hãy thêm dòng này
use App\Models\Category;


class HomeController extends Controller 
{ 
    public function index() {
        // --- Phần code gốc của bạn (Giữ nguyên) ---
        $db = Database::getConnection();
        $bookModel = new Book($db);
        $categoryModel = new Category($db);
        
        $latestBooks = $bookModel->getAllBooks();
        $featuredBooks = $bookModel->getTopViewed(4);
        $popularCategories = $categoryModel->getList(6); 

        // --- Sửa lại phần return ---
        // Gộp tất cả dữ liệu vào một mảng để truyền đi
        $data = [
            'pageTitle' => 'Trang Chủ - Thư Viện Sách',
            'books' => $latestBooks,         // Giữ lại 'books' cho code cũ
            'featuredBooks' => $featuredBooks,
            'popularCategories' => $popularCategories  // === BƯỚC 2: THÊM DỮ LIỆU MỚI VÀO ĐÂY ===
        ];
        
        // Gọi hàm view() với toàn bộ dữ liệu
        return $this->view('home', $data);
    }
}