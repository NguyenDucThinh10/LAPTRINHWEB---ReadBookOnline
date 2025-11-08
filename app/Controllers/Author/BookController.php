<?php
// File: app/Controllers/Author/BookController.php

namespace App\Controllers\Author;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Category;

class BookController extends Controller
{
    private $db;

    // Bảo mật: Đảm bảo chỉ 'author' mới vào được
    public function __construct()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'author') {
            header('Location: /');
            exit;
        }
        $this->db = Database::getConnection();
    }

    /**
     * Hiển thị form "Thêm sách mới"
     * Hàm này được gọi bởi: GET /author/books/create
     */
    public function create()
    {
        // Lấy danh sách thể loại để hiển thị trong dropdown
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAll();

        return $this->view('author/books/create', [
            'categories' => $categories
        ]);
    }

    /**
     * Xử lý dữ liệu từ form "Thêm sách mới"
     * Hàm này được gọi bởi: POST /author/books/store
     */
    public function store()
    {
        // 1. Lấy dữ liệu cơ bản từ form
        $title = $_POST['title'];
        $description = $_POST['description'];
        $categoryId = $_POST['category_id'];
        $chapters = $_POST['chapters'] ?? []; // Lấy mảng các chương

        // 2. Xử lý upload ảnh bìa
        $coverImagePath = $this->handleCoverUpload($_FILES['cover_image']);
        if (!$coverImagePath) {
            // Xử lý lỗi (ví dụ: quay lại form với thông báo)
            // Tạm thời:
            die('Lỗi upload ảnh bìa. Vui lòng thử lại.');
        }

        // 3. BẮT ĐẦU TRANSACTION (GIAO DỊCH AN TOÀN)
        $this->db->beginTransaction();

        try {
            // 4. THAO TÁC 1: Tạo sách mới
            $bookModel = new Book($this->db);
            $bookId = $bookModel->create([
                'title' => $title,
                'author' => $_SESSION['username'], // Lấy tên tác giả từ session
                'category_id' => $categoryId,
                'description' => $description,
                'cover_image' => $coverImagePath,
            ]);

            // 5. THAO TÁC 2: Lặp và tạo các chương
            $chapterModel = new Chapter($this->db);
            foreach ($chapters as $index => $chapterData) {
                $chapterModel->create([
                    'book_id' => $bookId,
                    'chapter_number' => $index + 1, // Số chương bắt đầu từ 1
                    'title' => $chapterData['title'],
                    'content' => $chapterData['content'],
                ]);
            }

            // 6. Nếu mọi thứ thành công, LƯU VĨNH VIỄN
            $this->db->commit();

            // 7. Chuyển hướng về trang dashboard với thông báo thành công
            header('Location: /author/dashboard?status=book_created');
            exit;

        } catch (\Exception $e) {
            // 8. Nếu có lỗi, HỦY BỎ tất cả thay đổi
            $this->db->rollBack();

            // (Nên ghi $e->getMessage() vào log)
            die('Đã có lỗi xảy ra trong quá trình lưu. Dữ liệu đã được khôi phục. Vui lòng thử lại.');
        }
    }

    /**
     * Hàm phụ trợ xử lý việc upload ảnh bìa
     */
    private function handleCoverUpload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false; // Có lỗi
        }

        // Đường dẫn thư mục lưu ảnh (tính từ gốc dự án)
        $targetDir = ROOT_PATH . '/public/uploads/covers/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); 
        }

        // Tạo tên file duy nhất (để tránh ghi đè)
        $fileName = uniqid() . '-' . basename($file['name']);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Trả về đường dẫn tương đối (để lưu vào DB và hiển thị trên web)
            return '/uploads/covers/' . $fileName;
        }

        return false;
    }
}