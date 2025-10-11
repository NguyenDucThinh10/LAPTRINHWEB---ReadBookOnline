<?php
use App\Core\Router;

// Hiển thị form đăng nhập/đăng ký
Router::get('/auth/login', 'Auth\AuthController@showLoginForm');

// Xử lý đăng nhập
Router::post('/auth/login', 'Auth\AuthController@login');

// Xử lý đăng ký
Router::post('/auth/signup', 'Auth\AuthController@signup');
