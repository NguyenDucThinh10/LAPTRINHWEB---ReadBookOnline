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

// (Thêm các route khác của bạn ở đây)
Router::get('/book/detail/{id}', 'BookController@detail');
Router::get('/book/show', 'BookController@show');
Router::get('/chapter/read/{id}', 'ChapterController@read');
Router::get('/shelf/index', 'ShelfController@index');
Router::get('/shelf',       'ShelfController@index');

// Thêm sách vào tủ
Router::post('/shelf/add', 'ShelfController@add');

// Cập nhật trạng thái đọc (Muốn đọc → Đang đọc → Đã đọc)
Router::post('/shelf/status', 'ShelfController@updateStatus');

// Xóa sách khỏi tủ
Router::post('/shelf/remove', 'ShelfController@remove');
Router::get('/search', 'BookController@search');
Router::get('/category/{id}', 'BookController@listByCategory');