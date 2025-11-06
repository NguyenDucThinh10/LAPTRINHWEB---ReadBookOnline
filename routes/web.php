<?php
// File: routes/web.php
use App\Core\Router;

// Route cho trang chủ
Router::get('/', 'HomeController@index');

// --- CÁC ROUTE XÁC THỰC ---
// Hiển thị form đăng nhập/đăng ký
Router::get('/auth/login', 'Auth\AuthController@showLoginForm');

// Xử lý đăng nhập
Router::post('/auth/login', 'Auth\AuthController@login');

// Xử lý đăng ký
Router::post('/auth/signup', 'Auth\AuthController@signup');

// Xử lý đăng xuất
Router::get('/auth/logout', 'Auth\AuthController@logout');

// (Thêm các route khác của bạn ở đây)