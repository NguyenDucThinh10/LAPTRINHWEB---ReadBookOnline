<?php
// File: app/Controllers/Author/DashboardController.php

namespace App\Controllers\Author;

use App\Core\Controller;
use App\Models\Book;          // Model
use App\Core\Database;      // ✅ Thêm Database để lấy kết nối

class DashboardController extends Controller
{
    /**
     * HÀM BẢO MẬT: Chạy đầu tiên
     */
    public function __construct()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'author') {
            header('Location: /');
            exit;
        }
    }

    /**
     * ✅ ĐÃ CẬP NHẬT
     * Hàm này được gọi bởi route: /author/dashboard
     */
    public function index()
    {
        // 1. Lấy tên tác giả từ session
        $authorName = $_SESSION['username']; 
        
        // 2. ✅ Sửa lỗi: Phải khởi tạo Model theo đúng cách của bạn
        $db_connection = Database::getConnection(); // Lấy kết nối
        $bookModel = new Book($db_connection);      // Khởi tạo đối tượng Book
        
        // 3. ✅ Sửa lỗi: Gọi hàm (non-static) từ đối tượng đã khởi tạo
        $books = $bookModel->findByAuthorName($authorName);

        // 4. Hiển thị view và truyền danh sách sách ($books) sang
        return $this->view('author/dashboard', [
            'books' => $books
        ]);
    }
}