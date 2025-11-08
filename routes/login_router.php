<?php
// File: routes/login_router.php
use App\Controllers\Auth\AuthController;

// Đường dẫn bây giờ rất sạch sẽ
$router->get('auth/login', function() {
    (new AuthController())->showLoginForm();
});

$router->post('auth/login', function() {
    (new AuthController())->login();
});

$router->post('auth/singups', function() { // Lưu ý: có thể bạn gõ nhầm 'singups' thay vì 'signup'
    (new AuthController())->signup();
});