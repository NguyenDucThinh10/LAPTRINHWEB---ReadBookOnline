<?php
// File: public/index.php

// Định nghĩa BASE_URL
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/WebReadBook/LAPTRINHWEB---ReadBookOnline/public');

// --- BỘ ĐỊNH TUYẾN (ROUTER) THÔNG MINH ---

// 1. Lấy controller từ URL, nếu không có thì mặc định là 'home'
$controllerName = $_GET['controller'] ?? 'home';

// 2. Lấy action (hành động) từ URL, nếu không có thì mặc định là 'index'
$actionName = $_GET['action'] ?? 'index';

// 3. Xây dựng tên Class và đường dẫn File Controller
// Ví dụ: controller 'book' -> class 'BookController' -> file 'BookController.php'
$controllerClassName = ucfirst($controllerName) . 'Controller';
$controllerFile = '../app/Controllers/' . $controllerClassName . '.php';

// 4. KIỂM TRA: File controller có tồn tại không?
if (file_exists($controllerFile)) {
    
    require_once $controllerFile;

    // 5. KIỂM TRA: Class có tồn tại bên trong file đó không?
    if (class_exists($controllerClassName)) {

        $controllerInstance = new $controllerClassName();

        // 6. KIỂM TRA: Phương thức (action) có tồn tại trong class đó không?
        if (method_exists($controllerInstance, $actionName)) {
            
            // 7. MỌI THỨ ĐỀU ĐÚNG -> Gọi phương thức
            $controllerInstance->$actionName();

        } else {
            // Lỗi: không tìm thấy phương thức
            die("Lỗi 404: Hành động '$actionName' không tồn tại trong controller '$controllerClassName'.");
        }
    } else {
        // Lỗi: không tìm thấy class
        die("Lỗi 404: Class '$controllerClassName' không được định nghĩa trong file '$controllerFile'.");
    }
} else {
    // Lỗi: không tìm thấy file
    die("Lỗi 404: File controller '$controllerFile' không tìm thấy.");
}