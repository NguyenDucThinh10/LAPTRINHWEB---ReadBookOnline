<?php
// File: app/Controllers/Admin/DashboardController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\AuthorRequest;
use App\Models\Book;     // ✅ Thêm Model Book
use App\Models\Category; // ✅ Thêm Model Category
use App\Core\Database;

class DashboardController extends Controller
{
    private $db; // ✅ Thêm thuộc tính db để dùng cho Model non-static

    // Bảo mật: Đảm bảo chỉ Admin mới vào được
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['role']) || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        $this->db = Database::getConnection();
    }

    // =================================================================
    // ✅ HÀM INDEX ĐÃ ĐƯỢC CẬP NHẬT ĐỂ HIỂN THỊ THỐNG KÊ
    // =================================================================
    /**
     * Hàm này được gọi bởi route: /admin/dashboard
     * Hiển thị Bảng điều khiển chính với các số liệu thống kê.
     */
    public function index()
    {
        // 1. Khởi tạo Models (Book và Category dùng instance pattern)
        $bookModel = new Book($this->db);
        $categoryModel = new Category($this->db);

        // 2. Lấy số liệu thống kê từ tất cả các Model
        $stats = [
            // Book::countAll() (Bạn cần thêm hàm này vào Book.php)
            'total_books'    => $bookModel->countAll(),
            
            // User::countAll() (Bạn cần thêm hàm này vào User.php)
            'total_users'    => User::countAll(),
            
            // Category::countAll() (Bạn cần thêm hàm này vào Category.php)
            'total_categories' => $categoryModel->countAll(),
            
            // AuthorRequest::countPending() (Bạn cần thêm hàm này vào AuthorRequest.php)
            'pending_authors' => AuthorRequest::countPending()
        ];

        // 3. Hiển thị View dashboard
        return $this->view('admin/dashboard', [
            'stats' => $stats
        ]);
    }

    /**
     * Hiển thị danh sách các yêu cầu làm tác giả
     * (Hàm này giữ nguyên)
     */
    public function showAuthorRequests()
    {
        $requests = AuthorRequest::getPendingRequests();
        
        return $this->view('admin/author_requests', [
            'requests' => $requests
        ]);
    }

    /**
     * XỬ LÝ DUYỆT TÁC GIẢ
     * (Hàm này giữ nguyên)
     */
    public function approveAuthor()
    {
        if (empty($_POST['request_id']) || empty($_POST['user_id']) || empty($_POST['author_name'])) {
            header('Location: /admin/author-requests?error=missing_data');
            exit;
        }
        
        $requestId = $_POST['request_id'];
        $userId = $_POST['user_id'];
        $authorName = $_POST['author_name'];

        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            AuthorRequest::updateStatus($requestId, 'approved');
            User::upgradeToAuthor($userId, $authorName);
            
            $db->commit();
            header('Location: /admin/author-requests?status=approved');
            exit;
        } catch (\Exception $e) {
            $db->rollBack();
            header('Location: /admin/author-requests?error=approve_failed');
            exit;
        }
    }

    /**
     * XỬ LÝ TỪ CHỐI TÁC GIẢ
     * (Hàm này giữ nguyên)
     */
    public function rejectAuthor()
    {
        if (empty($_POST['request_id'])) {
            header('Location: /admin/author-requests');
            exit;
        }
        
        $requestId = $_POST['request_id'];

        AuthorRequest::updateStatus($requestId, 'rejected');
        
        header('Location: /admin/author-requests?status=rejected');
        exit;
    }    
}