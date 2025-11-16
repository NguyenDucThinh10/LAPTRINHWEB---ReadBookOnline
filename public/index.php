<?php
// //if (false) {
// // ======= BẮT ĐẦU ĐOẠN CODE KHÔNG CHẠY =======
// // File: public/index.php

// /**
// * --------------------------------------------------------------------------
// * PHẦN 1: KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
// * --------------------------------------------------------------------------
// */

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// define('ROOT_PATH', dirname(__DIR__));
// // Tự động xác định BASE_URL dựa trên server, hoạt động với mọi cổng (80, 8080...)
// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
// $host = $_SERVER['HTTP_HOST']; // HTTP_HOST đã bao gồm cả port, ví dụ: "localhost:8080"

// // Tính toán đường dẫn thư mục con nếu có (ví dụ: /folder/public)
// $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// // Loại bỏ '/public' khỏi đường dẫn để trỏ về thư mục gốc của URL
// $scriptName = str_replace('/public', '', $scriptName);
// $scriptName = ($scriptName === '/') ? '' : $scriptName;

// define('BASE_URL', $protocol . $host . $scriptName);
// /**
// * --------------------------------------------------------------------------
// * PHẦN 2: AUTOLOADER
// * --------------------------------------------------------------------------
// */

// spl_autoload_register(function ($className) {
// $prefix = 'App\\';
// if (strpos($className, $prefix) !== 0) {
// return;
// }
// $relativeClass = substr($className, strlen($prefix));
// $filePath = ROOT_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';
// if (file_exists($filePath)) {
// require_once $filePath;
// }
// });

// /**
// * --------------------------------------------------------------------------
// * PHẦN 3: ĐỊNH TUYẾN (ROUTING)
// * --------------------------------------------------------------------------
// */

// // Nạp file định nghĩa các routes
// require_once ROOT_PATH . '/routes/web.php';

// // Lấy URI từ yêu cầu của người dùng (ví dụ: /auth/login)
// $uri = $_SERVER['REQUEST_URI'];

// // Lấy phương thức HTTP (GET, POST, ...)
// $method = $_SERVER['REQUEST_METHOD'];

// // Bắt đầu phiên làm việc (session) để có thể dùng cho đăng nhập, giỏ hàng...
// if (session_status() === PHP_SESSION_NONE) {
// session_start();
// }


// /**
// * --------------------------------------------------------------------------
// * PHẦN 4: THỰC THI
// * --------------------------------------------------------------------------
// */

// // Yêu cầu Router tìm và thực thi controller/action tương ứng
// App\Core\Router::dispatch($uri, $method);
// // ======= KẾT THÚC ĐOẠN CODE KHÔNG CHẠY =======
// }

// // ======= Mã index.php NguyenThanhTiep =======
// // File: public/index.php

// /**
// * --------------------------------------------------------------------------
// * PHẦN 1: KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
// * --------------------------------------------------------------------------
// */

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// define('ROOT_PATH', dirname(__DIR__));
// // Tự động xác định BASE_URL dựa trên server, hoạt động với mọi cổng (80, 8080...)
// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
// $host = $_SERVER['HTTP_HOST']; // HTTP_HOST đã bao gồm cả port, ví dụ: "localhost:8080"

// // Tính toán đường dẫn thư mục con nếu có (ví dụ: /folder/public)
// $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// // Loại bỏ '/public' khỏi đường dẫn để trỏ về thư mục gốc của URL
// $scriptName = str_replace('/public', '', $scriptName);
// $scriptName = ($scriptName === '/') ? '' : $scriptName;

// define('BASE_URL', $protocol . $host . $scriptName);
// /**
// * --------------------------------------------------------------------------
// * PHẦN 2: AUTOLOADER
// * --------------------------------------------------------------------------
// */

// spl_autoload_register(function ($className) {
// $prefix = 'App\\';
// if (strpos($className, $prefix) !== 0) {
// return;
// }
// $relativeClass = substr($className, strlen($prefix));
// $filePath = ROOT_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';
// if (file_exists($filePath)) {
// require_once $filePath;
// }
// });

// /**
// * --------------------------------------------------------------------------
// * PHẦN 3: ĐỊNH TUYẾN (ROUTING)
// * --------------------------------------------------------------------------
// */

// // Nạp file định nghĩa các routes
// require_once ROOT_PATH . '/routes/web.php';
// // Nạp routes tủ sách (của bạn)
// $customShelfRoutes = ROOT_PATH . '/routes/shelf_router.php';
// if (file_exists($customShelfRoutes)) {
// require_once $customShelfRoutes;
// }


// // Lấy URI CHỈ phần path (bỏ query string)
// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// // Loại bỏ tiền tố nếu app đặt trong thư mục con (vd: /ReadBookOnline/public)
// $publicBase = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/');
// if ($publicBase && $publicBase !== '/' && str_starts_with($uri, $publicBase)) {
// $uri = substr($uri, strlen($publicBase)) ?: '/';
// }

// // Lấy phương thức HTTP (GET, POST, ...)
// $method = $_SERVER['REQUEST_METHOD'];

// // Bắt đầu phiên làm việc (session) để có thể dùng cho đăng nhập, giỏ hàng...
// if (session_status() === PHP_SESSION_NONE) {
// session_start();
// }


// /**
// * --------------------------------------------------------------------------
// * PHẦN 4: THỰC THI
// * --------------------------------------------------------------------------
// */

// // Yêu cầu Router tìm và thực thi controller/action tương ứng
// App\Core\Router::dispatch($uri, $method);
//======= Mã index.php NguyenThanhTiep =======



//======= MA INDEX.PHP CUA LEVANTHANH =======
// File: public/index.php

// File: public/index.php

/**
 * --------------------------------------------------------------------------
 * PHẦN 1: KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
 * --------------------------------------------------------------------------
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));

// BASE_URL PHẢI TRỎ ĐẾN THƯ MỤC 'PUBLIC'
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptName = ($scriptName === '/') ? '' : $scriptName;
define('BASE_URL', $protocol . $host . $scriptName);


/**
 * --------------------------------------------------------------------------
 * PHẦN 2: AUTOLOADER
 * --------------------------------------------------------------------------
 */
spl_autoload_register(function ($className) {
    $prefix = 'App\\';
    if (strpos($className, $prefix) !== 0) return;
    $relativeClass = substr($className, strlen($prefix));
    $filePath = ROOT_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($filePath)) require_once $filePath;
});


/**
 * --------------------------------------------------------------------------
 * PHẦN 3: ĐỊNH TUYẾN VÀ THỰC THI
 * --------------------------------------------------------------------------
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uri = $_GET['url'] ?? '/';
$uri = '/' . trim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

require_once ROOT_PATH . '/routes/web.php';
// Bạn có thể nạp các file route khác ở đây nếu cần

App\Core\Router::dispatch($uri, $method);