<?php
// Hiển thị lỗi để dễ debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Đường dẫn gốc
define('ROOT_PATH', dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Autoloader thủ công cho namespace "App\"
|--------------------------------------------------------------------------
| Tự động chuyển đổi "App\Core\Controller" => "app/Core/Controller.php"
| Không cần composer.json hay vendor/autoload.php
*/
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/';

    // Kiểm tra class có thuộc namespace App\ không
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Bỏ qua nếu không phải App\
    }

    // Lấy phần tên class sau "App\"
    $relative_class = substr($class, $len);

    // Chuyển namespace thành đường dẫn thật
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Nạp file nếu tồn tại
    if (file_exists($file)) {
        require $file;
    }
});

// Gọi các class cần thiết
use App\Core\Router;
use App\Controllers\Auth\AuthController;

// Tạo router
$router = new Router();

// ===============================
// Định nghĩa routes
// ===============================

// Hiển thị trang login/signup
$router->get('/auth/login', function() {
    $controller = new AuthController();
    $controller->showLoginForm();
});

// Xử lý đăng ký
$router->post('/auth/signup', function() {
    $controller = new AuthController();
    $controller->signup();
});

// Xử lý đăng nhập
$router->post('/auth/login', function() {
    $controller = new AuthController();
    $controller->login();
});

// Trang chủ
$router->get('/', function() {
    require ROOT_PATH . '/app/Views/home.php';
});

// Chạy router
$router->run();
