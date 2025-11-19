<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Review;
use App\Models\Category; // ✅ Thêm Category Model

class BookController
{
    public function detail($id)
    {
        $bookId = (int)$id;
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
        
        // ==================================================
        // ✅ BẮT ĐẦU PHẦN FIX LỖI (SỬA TÊN BIẾN)
        // ==================================================
        
        // Sửa $total thành $totalReviews
        $totalReviews = $rvModel->countByBook($bookId);
        
        // Sửa $avg thành $avgRating
        $avgRating = $rvModel->avgByBook($bookId);

        // ==================================================
        // ✅ KẾT THÚC PHẦN FIX LỖI
        // ==================================================

        if (session_status()===PHP_SESSION_NONE) session_start();
        $myReview = !empty($_SESSION['user_id'])
            ? $rvModel->findByBookAndUser($bookId, (int)$_SESSION['user_id'])
            : null;

        require_once ROOT_PATH . '/app/Views/books/show.php';
    }

    public function search() {
        $keyword = $_GET['q'] ?? '';

        $db = Database::getConnection();
        $bookModel = new Book($db);
        
        $books = $bookModel->searchByTitle($keyword);
        
        $pageTitle = "Kết quả tìm kiếm: " . htmlspecialchars($keyword);

        require_once ROOT_PATH . '/app/Views/books/search_results.php';
    }
    
    public function listByCategory($id) {
        // 1. Giữ nguyên logic lấy ID
        $categoryId = (int)$id;
        if (!$categoryId) {
            die("Thiếu ID thể loại.");
        }

        $db = Database::getConnection();

        // 2. Giữ nguyên logic lấy sách
        $bookModel = new Book($db);
        $books = $bookModel->findByCategoryId($categoryId);
        
        // 3. Giữ nguyên logic lấy thông tin thể loại
        $categoryModel = new Category($db);
        $category = $categoryModel->findById($categoryId);
        
        // =================================================================
        // ✅ CẬP NHẬT TẠI ĐÂY: Thêm biến $keyword
        // =================================================================
        // View 'search_results.php' cần biến $keyword để hiển thị tiêu đề
        // Ta gán tên thể loại vào biến này.
        $keyword = $category['name'] ?? 'Không rõ';

        // 4. Giữ nguyên logic tiêu đề trang
        $pageTitle = "Thể loại: " . htmlspecialchars($keyword);

        // 5. Gọi view (Giữ nguyên)
        require_once ROOT_PATH . '/app/Views/books/search_results.php';
    }
}