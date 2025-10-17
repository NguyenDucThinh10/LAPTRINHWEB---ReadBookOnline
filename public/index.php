<!-- ĐIỂM TRUY CẬP DUY NHẤT (Front Controller) -->
<?php
// File: public/index.php

// --------------------------------------------------------------------------
// PHẦN 1: KHỞI TẠO ỨNG DỤNG (Lấy từ nhánh main)
// --------------------------------------------------------------------------

// Hiển thị lỗi để dễ debug trong quá trình phát triển
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Định nghĩa các đường dẫn gốc
define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/LAPTRINHWEB---ReadBookOnline/public');

// Autoloader: "Cỗ máy" tự động require file khi cần
// Đây là phần ma thuật lấy từ nhánh main, cực kỳ hữu ích.
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// --------------------------------------------------------------------------
// PHẦN 2: BỘ ĐỊNH TUYẾN (Kết hợp cả hai ý tưởng)
// --------------------------------------------------------------------------

// Lấy controller và action từ URL theo cách của bạn, rất đơn giản và hiệu quả.
$controllerName = $_GET['controller'] ?? 'home';
$actionName = $_GET['action'] ?? 'index';

// Xây dựng tên Class và đường dẫn file
// Chúng ta sẽ thêm namespace 'App\Controllers\' vào trước tên class
$controllerClassName = 'App\\Controllers\\' . ucfirst($controllerName) . 'Controller';

// Kiểm tra và thực thi
if (class_exists($controllerClassName)) {
    $controllerInstance = new $controllerClassName();

    if (method_exists($controllerInstance, $actionName)) {
        // Mọi thứ đều đúng -> Gọi phương thức
        $controllerInstance->$actionName();
    } else {
        die("Lỗi 404: Hành động '$actionName' không tồn tại trong controller '$controllerClassName'.");
    }
} else {
    // Để autoloader hoạt động, chúng ta cần kiểm tra lại logic này một chút
    // Ta sẽ giả định nếu class không tồn tại sau khi autoloader chạy, thì nó là lỗi 404.
    die("Lỗi 404: Controller '$controllerClassName' không tìm thấy.");
}