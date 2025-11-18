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

// Route cho trang tài khoản cá nhân

Router::get('/account', 'UserController@profile');

// Xử lý yêu cầu POST từ form đổi mật khẩu

Router::post('/account/change-password', 'UserController@changePassword');

// (Thêm các route khác của bạn ở đây)



// --- 1. ROUTE CHO NGƯỜI ĐỌC YÊU CẦU ---

// Hiển thị form đăng ký

Router::get('/account/apply-author', 'UserController@showAuthorApplicationForm');

// Xử lý khi nộp form

Router::post('/account/apply-author', 'UserController@handleAuthorApplication');





// --- 2. ROUTE CHO ADMIN DUYỆT ---

// Hiển thị trang danh sách yêu cầu

Router::get('/admin/author-requests', 'Admin\DashboardController@showAuthorRequests');

// Xử lý khi admin nhấn nút "Duyệt"

Router::post('/admin/approve-author', 'Admin\DashboardController@approveAuthor');



// --- CÁC ROUTE DÀNH CHO ADMIN ---

Router::get('/admin/dashboard', 'Admin\DashboardController@index'); // Trang admin chính



// ✅ CÁC ROUTE DÀNH CHO TÁC GIẢ

// =======================================================

// Trang Bảng điều khiển chính (chúng ta đang xây dựng)

Router::get('/author/dashboard', 'Author\DashboardController@index');



// Trang hiển thị form "Thêm sách mới" (đã tạo)

Router::get('/author/books/create', 'Author\BookController@create');



// Route xử lý việc nộp form "Thêm sách mới" (đã tạo)

Router::post('/author/books/store', 'Author\BookController@store');


// ...XỬ LÝ YÊU CẦU TỪ CHỐI TÁC

Router::post('/admin/approve-author', 'Admin\DashboardController@approveAuthor');

// ✅ THÊM DÒNG MỚI NÀY:

Router::post('/admin/reject-author', 'Admin\DashboardController@rejectAuthor');

// ... (các route /admin/author-requests ...)



// ✅ THÊM CÁC ROUTE QUẢN LÝ USER

// Hiển thị trang danh sách user

Router::get('/admin/users', 'Admin\UserController@index');

// Xử lý khi Admin thay đổi role

Router::post('/admin/users/update-role', 'Admin\UserController@updateRole');

// Xử lý khi Admin xóa user
Router::post('/admin/users/delete', 'Admin\UserController@deleteUser');
// File: routes/web.php
// ...

// ===========================================
// TRANG QUẢN TRỊ (ADMIN)
// ===========================================
// ... (Các route /admin/dashboard, /admin/author-requests, /admin/users) ...

// ✅ BẮT ĐẦU NHÓM 3: QUẢN LÝ SÁCH (BẠN ĐANG THIẾU NHÓM NÀY)
// 1. (R)ead: Hiển thị danh sách tất cả sách
Router::get('/admin/books', 'Admin\BookController@index');

// 2. (U)pdate (Part 1): Hiển thị form để sửa sách
Router::get('/admin/books/edit', 'Admin\BookController@edit');

// 3. (U)pdate (Part 2): Xử lý dữ liệu từ form sửa
Router::post('/admin/books/update', 'Admin\BookController@update');

// 4. (D)elete: Xử lý xóa sách
Router::post('/admin/books/delete', 'Admin\BookController@delete');
// ✅ KẾT THÚC NHÓM 3
// File: routes/web.php
// ... (Các route Admin cũ) ...

// ✅ BẮT ĐẦU NHÓM 4: QUẢN LÝ THỂ LOẠI
// 1. (R)ead: Hiển thị danh sách & form thêm mới
Router::get('/admin/categories', 'Admin\CategoryController@index');

// 2. (C)reate: Xử lý thêm mới
Router::post('/admin/categories/store', 'Admin\CategoryController@store');

// 3. (U)pdate (Part 1): Hiển thị form sửa
Router::get('/admin/categories/edit', 'Admin\CategoryController@edit');

// 4. (U)pdate (Part 2): Xử lý cập nhật
Router::post('/admin/categories/update', 'Admin\CategoryController@update');

// 5. (D)elete: Xử lý xóa
Router::post('/admin/categories/delete', 'Admin\CategoryController@delete');
// ✅ KẾT THÚC NHÓM 4

// (Thêm các route khác của bạn ở đây)
Router::get('/book/detail', 'BookController@detail');
Router::get('/book/show', 'BookController@show');

// Reviews
Router::post('/reviews/add',    'ReviewController@add');
Router::post('/reviews/update', 'ReviewController@update');
Router::post('/reviews/delete', 'ReviewController@delete');

// ===========================================
// Hiển thị trang Tủ sách (gọi hàm index)
Router::get('/shelf', 'ShelfController@index');

// Xử lý cập nhật status (Đang đọc, Đã đọc...)
Router::post('/shelf/status', 'ShelfController@updateStatus');

// Xử lý xóa sách khỏi tủ
Router::post('/shelf/remove', 'ShelfController@remove');

// Xử lý thêm sách vào tủ
Router::post('/shelf/add', 'ShelfController@add');