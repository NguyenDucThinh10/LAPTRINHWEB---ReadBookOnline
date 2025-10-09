<?php
// File: public/index.php

// Định nghĩa một hằng số cho đường dẫn, giúp link CSS/JS dễ dàng hơn
// Nhớ thay 'WebReadBook/LAPTRINHWEB---ReadBookOnline' bằng tên thư mục gốc của bạn nếu khác
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/WebReadBook/LAPTRINHWEB---ReadBookOnline/public');

// 1. Gọi file Controller vào, đây là "Người quản lý"
require_once '../app/Controllers/HomeController.php';

// 2. Tạo một đối tượng từ lớp HomeController
$controller = new HomeController();

// 3. Ra lệnh cho "Người quản lý" làm việc (chạy phương thức index)
$controller->index();