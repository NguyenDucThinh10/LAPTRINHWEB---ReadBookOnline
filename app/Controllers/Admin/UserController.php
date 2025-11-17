<?php
// File: app/Controllers/Admin/UserController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    // Bảo mật: Đảm bảo chỉ Admin mới vào được
    public function __construct()
    {
        // Thêm kiểm tra $_SESSION['user_id'] để đảm bảo session đã khởi tạo
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /auth/login'); // Chuyển về trang login nếu chưa đăng nhập
            exit;
        }
    }

    /**
     * Hiển thị danh sách tất cả người dùng
     * (Được gọi bởi: GET /admin/users)
     */
    public function index()
    {
        $users = User::getAll();
        
        return $this->view('admin/users', [
            'users' => $users
        ]);
    }

    /**
     * Xử lý cập nhật vai trò (role)
     * (Được gọi bởi: POST /admin/users/update-role)
     */
    public function updateRole()
    {
        if (empty($_POST['user_id']) || empty($_POST['new_role'])) {
            header('Location: /admin/users?error=missing_data');
            exit;
        }

        $userId = $_POST['user_id'];
        $newRole = $_POST['new_role'];
        $currentAdminId = $_SESSION['user_id'];

        // Logic bảo mật: Không cho Admin tự hạ vai trò của chính mình
        if ($userId == $currentAdminId && $newRole !== 'admin') {
             header('Location: /admin/users?error=cannot_demote_self');
             exit;
        }

        User::updateRole($userId, $newRole);
        
        header('Location: /admin/users?status=role_updated');
        exit;
    }

    // =================================================================
    // ✅ HÀM MỚI BẠN CÒN THIẾU
    // =================================================================
    /**
     * Xử lý xóa người dùng
     * (Được gọi bởi: POST /admin/users/delete)
     */
    public function deleteUser()
    {
        if (empty($_POST['user_id'])) {
            header('Location: /admin/users?error=missing_data');
            exit;
        }

        $userIdToDelete = $_POST['user_id'];
        $currentAdminId = $_SESSION['user_id'];

        // Logic bảo mật: Không cho Admin tự xóa chính mình
        if ($userIdToDelete == $currentAdminId) {
            header('Location: /admin/users?error=cannot_delete_self');
            exit;
        }

        // Gọi Model để xóa
        User::delete($userIdToDelete);
        
        header('Location: /admin/users?status=user_deleted');
        exit;
    }
}