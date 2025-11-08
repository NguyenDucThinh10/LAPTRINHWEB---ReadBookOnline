﻿<?php
// File: public/index.php

/**
 * --------------------------------------------------------------------------
 * PHẦN 1: KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
 * --------------------------------------------------------------------------
 * Đây là nơi chúng ta thiết lập các hằng số và cài đặt quan trọng.
 */

// Bật hiển thị lỗi để dễ dàng gỡ lỗi trong quá trình phát triển
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Định nghĩa ROOT_PATH: Đường dẫn vật lý đến thư mục gốc của dự án (ví dụ: D:/.../LAPTRINHWEB---ReadBookOnline)
// Cách này luôn luôn đúng, bất kể bạn đặt dự án ở đâu.
define('ROOT_PATH', dirname(__DIR__));

// Định nghĩa BASE_URL: Tự động tính toán đường dẫn URL đến thư mục public.
// Cách này sẽ hoạt động dù bạn đặt dự án ở thư mục con nào trong htdocs.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptName = ($scriptName === '/') ? '' : $scriptName;
define('BASE_URL', $protocol . $host . $scriptName);


/**
 * --------------------------------------------------------------------------
 * PHẦN 2: AUTOLOADER - CỖ MÁY TỰ ĐỘNG NẠP FILE
 * --------------------------------------------------------------------------
 * Phần "ma thuật" này giúp bạn không cần phải require_once các file class nữa.
 */

spl_autoload_register(function ($className) {
    // Chỉ xử lý các class có namespace bắt đầu bằng "App\"
    $prefix = 'App\\';
    if (strpos($className, $prefix) !== 0) {
        return;
    }

    // Chuyển đổi namespace thành đường dẫn file (ví dụ: App\Controllers\HomeController -> app/Controllers/HomeController.php)
    $relativeClass = substr($className, strlen($prefix));
    $filePath = ROOT_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    // Nạp file nếu nó tồn tại
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});


/**
 * --------------------------------------------------------------------------
 * PHẦN 3: BỘ ĐỊNH TUYẾN (ROUTER) - "NGƯỜI ĐIỀU PHỐI GIAO THÔNG"
 * --------------------------------------------------------------------------
 * Đọc URL và quyết định Controller nào, phương thức (action) nào sẽ được gọi.
 */

// Lấy controller từ URL, nếu không có thì mặc định là 'home'
$controllerName = $_GET['controller'] ?? 'home';

// Lấy action từ URL, nếu không có thì mặc định là 'index'
$actionName = $_GET['action'] ?? 'index';

// Xây dựng tên class đầy đủ (bao gồm cả namespace)
$controllerClassName = 'App\\Controllers\\' . ucfirst($controllerName) . 'Controller';


/**
 * --------------------------------------------------------------------------
 * PHẦN 4: THỰC THI
 * --------------------------------------------------------------------------
 * Khởi tạo và gọi Controller/Action tương ứng.
 */

if (class_exists($controllerClassName)) {
    $controllerInstance = new $controllerClassName();

    if (method_exists($controllerInstance, $actionName)) {
        $controllerInstance->$actionName();
    } else {
        http_response_code(404);
        die("Lỗi 404: Hành động (phương thức) '$actionName' không tồn tại trong controller '$controllerClassName'.");
    }
} else {
    http_response_code(404);
    die("Lỗi 404: Controller '$controllerClassName' không tồn tại. Vui lòng kiểm tra lại tên controller hoặc namespace.");
}