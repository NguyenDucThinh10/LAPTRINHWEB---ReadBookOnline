<?php
// File: app/Controllers/Admin/DashboardController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\AuthorRequest;
use App\Core\Database;

class DashboardController extends Controller
{
    // Bảo mật: Đảm bảo chỉ Admin mới vào được
    public function __construct()
    {
        if (!isset($_SESSION['role']) || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
    }

    // =================================================================
    // ✅ HÀM MỚI ĐƯỢC THÊM VÀO
    // =================================================================
    /**
     * Hàm này được gọi bởi route: /admin/dashboard
     * Đây là trang "Bảng điều khiển" chính.
     */
    public function index()
    {
        // Tạm thời, chúng ta sẽ chuyển hướng trang dashboard chính
        // đến trang "Duyệt yêu cầu" vì đó là việc quan trọng nhất.
        header('Location: /admin/author-requests');
        exit;
        
        // Hoặc sau này, bạn có thể tạo một view riêng cho dashboard
        // return $this->view('admin/dashboard_home');
    }

    /**
     * Hiển thị danh sách các yêu cầu làm tác giả
     * (Hàm này của bạn đã đúng)
     */
    public function showAuthorRequests()
    {
        $requests = AuthorRequest::getPendingRequests();
        
        return $this->view('admin/author_requests', [
            'requests' => $requests
        ]);
    }

    /**
     * XỬ LÝ DUYỆT TÁC GIẢ (CÓ TRANSACTION)
     * (Hàm này của bạn đã đúng)
     */
    public function approveAuthor()
    {
        // 1. Lấy dữ liệu từ form
        if (empty($_POST['request_id']) || empty($_POST['user_id']) || empty($_POST['author_name'])) {
            header('Location: /admin/author-requests?error=missing_data');
            exit;
        }
        
        $requestId = $_POST['request_id'];
        $userId = $_POST['user_id'];
        $authorName = $_POST['author_name'];

        // 2. Lấy kết nối DB và Bắt đầu Transaction
        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            // 3. VIỆC 1: Cập nhật bảng AuthorRequests
            AuthorRequest::updateStatus($requestId, 'approved');
            
            // 4. VIỆC 2: Cập nhật bảng Users
            User::upgradeToAuthor($userId, $authorName);
            
            // 5. Nếu cả 2 thành công, commit (lưu vĩnh viễn)
            $db->commit();
            
            // 6. Chuyển hướng với thông báo thành công
            header('Location: /admin/author-requests?status=approved');
            exit;
            
        } catch (\Exception $e) {
            // 7. Nếu 1 trong 2 việc bị lỗi, rollback (hủy bỏ) tất cả
            $db->rollBack();
            
            header('Location: /admin/author-requests?error=approve_failed');
            exit;
        }
    }
    /**
     * XỬ LÝ TỪ CHỐI TÁC GIẢ (CÓ TRANSACTION)
     * Hàm này được gọi bởi route: POST /admin/reject-author
     */
    /**
     * ✅ HÀM MỚI: Xử lý từ chối yêu cầu
     */
    public function rejectAuthor()
    {
        if (empty($_POST['request_id'])) {
            header('Location: /admin/author-requests');
            exit;
        }
        
        $requestId = $_POST['request_id'];

        // Gọi Model để cập nhật trạng thái
        // Chúng ta không cần Transaction vì chỉ cập nhật 1 bảng
        AuthorRequest::updateStatus($requestId, 'rejected');
        
        header('Location: /admin/author-requests?status=rejected');
        exit;
    }    
}