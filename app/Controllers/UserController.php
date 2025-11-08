<?php
// File: app/Controllers/UserController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\AuthorRequest; // ✅ Thêm Model mới

class UserController extends Controller
{
    /**
     * ✅ ĐÃ CẬP NHẬT
     * Hiển thị trang cá nhân, giờ sẽ lấy thêm thông tin yêu cầu (nếu có)
     */
    public function profile()
    {
        // Kiểm tra xem user_id có tồn tại trong session không
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        // Lấy thông tin người dùng từ database
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId); 

        // ✅ Lấy thêm thông tin về yêu cầu tác giả (nếu có)
        $authorRequest = AuthorRequest::findByUserId($userId);

        if (!$user) {
            session_destroy();
            header('Location: /');
            exit;
        }
        
        // Hiển thị view và truyền cả 2 biến vào
        return $this->view('user/profile', [
            'user' => $user,
            'authorRequest' => $authorRequest // ✅ Truyền dữ liệu request sang view
        ]);
    }

    /**
     * ✅ HÀM MỚI (Xử lý form "Lưu thay đổi")
     * Xử lý việc cập nhật thông tin cá nhân (ví dụ: email)
     */
    public function updateProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $email = trim($_POST['email']);

        // 1. Validation cơ bản
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /account?error=invalid_email');
            exit;
        }

        // 2. Kiểm tra xem email đã tồn tại bởi người dùng KHÁC chưa
        $existingUser = User::findByEmail($email);
        if ($existingUser && $existingUser['user_id'] != $userId) {
            header('Location: /account?error=email_exists');
            exit;
        }

        // 3. Cập nhật
        User::update($userId, ['email' => $email]);

        // 4. Chuyển hướng với thông báo thành công
        header('Location: /account?status=profile_updated');
        exit;
    }

    /**
     * Xử lý đổi mật khẩu (Giữ nguyên)
     */
    public function changePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            header('Location: /account?error=password_mismatch');
            exit;
        }

        $user = User::findWithPasswordById($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            header('Location: /account?error=current_password_invalid');
            exit;
        }

        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        User::updatePassword($userId, $hashedNewPassword);

        header('Location: /account?status=password_success');
        exit;
    }

    // =================================================================
    // ✅ BẮT ĐẦU PHẦN MỚI (ĐĂNG KÝ TÁC GIẢ)
    // =================================================================

    /**
     * Hiển thị form đăng ký làm tác giả
     */
    public function showAuthorApplicationForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);
        $request = AuthorRequest::findByUserId($userId);

        // Nếu đã là author HOẶC đã có request (bất kỳ trạng thái nào)
        // thì không cho đăng ký nữa, chuyển về trang cá nhân
        if ($user['role'] === 'author' || $request) { 
            header('Location: /account');
            exit;
        }

        // Nếu đủ điều kiện, hiển thị form
        return $this->view('user/apply_author');
    }

    /**
     * Xử lý form đăng ký tác giả
     */
    public function handleAuthorApplication()
    {
        if (!isset($_SESSION['user_id']) || empty($_POST['pen_name'])) {
            header('Location: /'); // Lỗi/Spam
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $penName = trim($_POST['pen_name']);

        // Tạo yêu cầu mới trong bảng AuthorRequests
        AuthorRequest::create($userId, $penName);
        
        // Chuyển hướng về trang cá nhân (họ sẽ thấy trạng thái "Đang chờ duyệt")
        header('Location: /account');
        exit;
    }
}