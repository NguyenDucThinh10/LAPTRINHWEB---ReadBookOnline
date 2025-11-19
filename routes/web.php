<?php
// File: routes/web.php (Phiên bản SẠCH SẼ, giữ logic cũ)
use App\Core\Router;

// ===========================================
// TRANG CHÍNH & XEM SÁCH (SỬ DỤNG LOGIC CŨ CỦA BẠN)
// ===========================================
Router::get('/', 'HomeController@index');
Router::get('/search', 'BookController@search');
Router::get('/book/detail/{id}', 'BookController@detail'); // <-- Khôi phục route động
Router::get('/category/{id}', 'BookController@listByCategory'); // <-- Khôi phục route động
Router::get('/chapter/read/{id}', 'ChapterController@read'); // <-- Khôi phục route động

// ===========================================
// XÁC THỰC (AUTH)
// ===========================================
Router::get('/auth/login', 'Auth\AuthController@showLoginForm');
Router::post('/auth/login', 'Auth\AuthController@login');
Router::post('/auth/signup', 'Auth\AuthController@signup');
Router::get('/auth/logout', 'Auth\AuthController@logout');

// ===========================================
// TRANG CÁ NHÂN (USER)
// ===========================================
Router::get('/account', 'UserController@profile');
Router::post('/account/update', 'UserController@updateProfile');
Router::post('/account/change-password', 'UserController@changePassword');
Router::get('/account/apply-author', 'UserController@showAuthorApplicationForm');
Router::post('/account/apply-author', 'UserController@handleAuthorApplication');

// ===========================================
// TỦ SÁCH (SHELF)
// ===========================================
Router::get('/shelf', 'ShelfController@index');
Router::post('/shelf/add', 'ShelfController@add');
Router::post('/shelf/status', 'ShelfController@updateStatus');
Router::post('/shelf/remove', 'ShelfController@remove');

// ===========================================
// ĐÁNH GIÁ (REVIEWS)
// ===========================================
Router::post('/reviews/add', 'ReviewController@add');
Router::post('/reviews/update', 'ReviewController@update');
Router::post('/reviews/delete', 'ReviewController@delete');

// ===========================================
// TRANG TÁC GIẢ (AUTHOR)
// ===========================================
Router::get('/author/dashboard', 'Author\DashboardController@index');
Router::get('/author/books/create', 'Author\BookController@create');
Router::post('/author/books/store', 'Author\BookController@store');

// ===========================================
// TRANG QUẢN TRỊ (ADMIN)
// ===========================================
Router::get('/admin/dashboard', 'Admin\DashboardController@index');

// Duyệt Tác giả (Đã xóa dòng bị trùng)
Router::get('/admin/author-requests', 'Admin\DashboardController@showAuthorRequests');
Router::post('/admin/approve-author', 'Admin\DashboardController@approveAuthor');
Router::post('/admin/reject-author', 'Admin\DashboardController@rejectAuthor');

// Quản lý User
Router::get('/admin/users', 'Admin\UserController@index');
Router::post('/admin/users/update-role', 'Admin\UserController@updateRole');
Router::post('/admin/users/delete', 'Admin\UserController@deleteUser');

// Quản lý Sách
Router::get('/admin/books', 'Admin\BookController@index');
Router::get('/admin/books/edit', 'Admin\BookController@edit');
Router::post('/admin/books/update', 'Admin\BookController@update');
Router::post('/admin/books/delete', 'Admin\BookController@delete');

// Quản lý Thể loại
Router::get('/admin/categories', 'Admin\CategoryController@index');
Router::get('/admin/categories/edit', 'Admin\CategoryController@edit');
Router::post('/admin/categories/store', 'Admin\CategoryController@store');
Router::post('/admin/categories/update', 'Admin\CategoryController@update');
Router::post('/admin/categories/delete', 'Admin\CategoryController@delete');
