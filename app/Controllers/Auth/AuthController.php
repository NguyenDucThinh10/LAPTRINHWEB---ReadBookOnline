<?php
namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Models\User;
use App\Core\Database;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập / đăng ký
    public function showLoginForm()
    {
        return $this->view('auth/login_signup');
    }

    // Xử lý đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->view('auth/login_signup');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Kiểm tra dữ liệu nhập đủ chưa
        if (empty($username) || empty($password)) {
            return $this->view('auth/login_signup', [
                'login_error' => 'Vui lòng nhập đầy đủ thông tin!',
                'username' => $username
            ]);
        }

        // Tìm user theo username hoặc email
        $user = User::findByUsernameOrEmail($username, $username);

        // Kiểm tra user tồn tại và mật khẩu đúng
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->view('auth/login_signup', [
                'login_error' => 'Tên đăng nhập hoặc mật khẩu không đúng!',
                'username' => $username
            ]);
        }

        // Đăng nhập thành công - Lưu thông tin vào session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Chuyển hướng theo role
        if ($user['role'] === 'admin') {
            header('Location: /admin/dashboard');
        } elseif ($user['role'] === 'author') {
            header('Location: /author/dashboard');
        } else {
            header('Location: /');
        }
        exit;
    }

    // Xử lý đăng ký người dùng mới
    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->view('auth/login_signup');
        }

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Kiểm tra dữ liệu nhập đủ chưa
        if (empty($username) || empty($email) || empty($password)) {
            return $this->view('auth/login_signup', [
                'register_error' => 'Vui lòng nhập đầy đủ thông tin!',
                'showRegister'   => true,
                'username' => $username,
                'email' => $email
            ]);
        }

        // ✅ Kiểm tra email đã tồn tại
        if (User::emailExists($email)) {
            return $this->view('auth/login_signup', [
                'register_error' => 'Email đã được đăng ký, vui lòng dùng email khác!',
                'showRegister'   => true,
                'username' => $username,
                'email' => $email
            ]);
        }

        // ✅ Kiểm tra username đã tồn tại
        if (User::usernameExists($username)) {
            return $this->view('auth/login_signup', [
                'register_error' => 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!',
                'showRegister'   => true,
                'username' => $username,
                'email' => $email
            ]);
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Lưu user mới
        $result = User::create([
            'username' => $username,
            'email'    => $email,
            'password' => $hashedPassword,
        ]);

        if ($result) {
            // Dọn output buffer để tránh lỗi
            while (ob_get_level()) ob_end_clean();
            $this->view('auth/register_success');
            exit;
        } else {
            return $this->view('auth/login_signup', [
                'register_error' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại!',
                'showRegister'   => true,
                'username' => $username,
                'email' => $email
            ]);
        }
    }

    // Xử lý đăng xuất
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Xóa toàn bộ session
        session_unset();
        session_destroy();
        
        // Chuyển về trang đăng nhập
        header('Location: /auth/login');
        exit;
    }
}