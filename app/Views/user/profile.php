<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản của tôi</title>
    <style>
        /* Sử dụng các biến màu và font từ file CSS của bạn */
        :root {
            --accent-color: #C5A992;
            --light-color: #F3F2EC;
            --dark-color: #2f2f2f;
            --body-text-color: #757575;
            --body-font: "Raleway", sans-serif;
            --heading-font: "Prata", Georgia, serif;
        }

        body {
            font-family: var(--body-font);
            background-color: var(--light-color);
            color: var(--body-text-color);
            line-height: 1.6;
            margin: 40px 0;
        }

        .profile-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            gap: 30px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.07);
        }

        /* --- Sidebar --- */
        .profile-sidebar {
            flex: 0 0 250px; /* Sidebar không co giãn, rộng 250px */
            border-right: 1px solid #E0E0E0;
            padding-right: 30px;
        }

        .profile-avatar {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-avatar h3 {
            font-family: var(--heading-font);
            color: var(--dark-color);
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 1.5em;
        }

        .profile-avatar p {
            color: var(--body-text-color);
            text-transform: capitalize;
            font-weight: 500;
        }
        
        .profile-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .profile-nav a {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--dark-color);
            border-radius: 6px;
            margin-bottom: 5px;
            transition: background-color 0.3s;
            font-weight: 500;
        }

        .profile-nav a:hover {
            background-color: #f7f3f0;
        }

        .profile-nav a.active {
            background-color: var(--accent-color);
            color: #fff;
            font-weight: 600;
        }
        
        .profile-nav a i {
            margin-right: 10px;
        }
        
        /* --- Main Content --- */
        .profile-content {
            flex-grow: 1; /* Phần nội dung sẽ chiếm hết không gian còn lại */
        }
        
        .profile-content h1 {
             font-family: var(--heading-font);
             color: #333;
             border-bottom: 2px solid var(--accent-color);
             padding-bottom: 10px;
             margin-top: 0;
             margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background-color: #fafafa;
            box-sizing: border-box;
        }
        
        .form-group input[disabled] {
            background-color: #eee;
            color: #888;
            cursor: not-allowed;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #b3927b; /* Màu đậm hơn một chút */
        }

        /* CSS cho thông báo */
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; border: 1px solid transparent; font-weight: 500;}
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="profile-container">
        <aside class="profile-sidebar">
            <div class="profile-avatar">
                <img src="https://i.pravatar.cc/150?u=<?= htmlspecialchars($user['username']) ?>" alt="Avatar">
                <h3><?= htmlspecialchars($user['username']) ?></h3>
                
                <?php if ($user['role'] === 'author' && !empty($user['author_name'])): ?>
                    <p>Bút danh: <?= htmlspecialchars($user['author_name']) ?></p>
                <?php else: ?>
                    <p><?= htmlspecialchars($user['role']) ?></p>
                <?php endif; ?>
            </div>
            <nav class="profile-nav">
                <ul>
                    <li><a href="/account" class="active"><i class="fas fa-user-circle"></i> Thông tin tài khoản</a></li>
                    
                    <?php
                    
                    ?>
                    <?php if ($user['role'] === 'author'): ?>
                        <li><a href="/author/dashboard"><i class="fas fa-book"></i> Sách của tôi</a></li>

                    <?php elseif ($authorRequest && $authorRequest['status'] === 'pending'): ?>
                        <li><a href="#" style="cursor: not-allowed; color: #999;"><i class="fas fa-clock"></i> Đang chờ duyệt</a></li>

                    <?php elseif ($authorRequest && $authorRequest['status'] === 'rejected'): ?>
                        <li><a href="/account/apply-author" style="color: red;"><i class="fas fa-exclamation-circle"></i> Yêu cầu bị từ chối (Gửi lại)</a></li>

                    <?php elseif ($user['role'] === 'reader' && !$authorRequest): ?>
                        <li><a href="/account/apply-author"><i class="fas fa-feather-alt"></i> Đăng ký tác giả</a></li>
                    
                    <?php endif; ?>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a></li>
                    <?php endif; ?>
                    <li><a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="profile-content">
            <h1>Thông tin tài khoản</h1>

            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success">
                    <?php
                        switch ($_GET['status']) {
                            case 'password_success':
                                echo 'Đổi mật khẩu thành công!';
                                break;
                            case 'profile_updated':
                                echo 'Cập nhật thông tin thành công!';
                                break;
                        }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                        switch ($_GET['error']) {
                            case 'password_mismatch':
                                echo 'Mật khẩu mới không khớp. Vui lòng thử lại.';
                                break;
                            case 'current_password_invalid':
                                echo 'Mật khẩu hiện tại không đúng.';
                                break;
                            case 'invalid_email':
                                echo 'Địa chỉ email không hợp lệ.';
                                break;
                            case 'email_exists':
                                echo 'Email này đã được sử dụng bởi một tài khoản khác.';
                                break;
                            default:
                                echo 'Đã có lỗi xảy ra.';
                        }
                    ?>
                </div>
            <?php endif; ?>
        
            <form action="/account/update" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                    <small>Tên đăng nhập không thể thay đổi.</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Ngày tham gia</label>
                    <input type="text" value="<?= date('d/m/Y', strtotime($user['created_at'])) ?>" disabled>
                </div>
                
                
            </form>

            <hr style="margin: 40px 0;">

            <h2>Đổi mật khẩu</h2>
            <form action="/account/change-password" method="POST">
                 <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                 <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                 <button type="submit" class="btn-primary">Đổi mật khẩu</button>
            </form>
        </main>
        </div>

</body>
</html>