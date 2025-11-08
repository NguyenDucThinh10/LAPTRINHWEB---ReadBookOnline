<?php
use App\Core\Router;

// ===============================
// CÁC ROUTE CHO CHỨC NĂNG TỦ SÁCH
// ===============================

// Hiển thị danh sách tủ sách của người dùng
Router::get('/shelf',       'ShelfController@index');
Router::get('/shelf/',      'ShelfController@index');
Router::get('/shelf/index', 'ShelfController@index');

// Thêm sách vào tủ
Router::post('/shelf/add', 'ShelfController@add');

// Cập nhật trạng thái đọc (Muốn đọc → Đang đọc → Đã đọc)
Router::post('/shelf/status', 'ShelfController@updateStatus');

// Xóa sách khỏi tủ
Router::post('/shelf/remove', 'ShelfController@remove');
