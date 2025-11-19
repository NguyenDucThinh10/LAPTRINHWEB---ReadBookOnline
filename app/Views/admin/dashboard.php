<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* --- Tái sử dụng biến màu --- */
        :root {
            --accent-color: #C5A992; --light-color: #F3F2EC; --dark-color: #2f2f2f;
            --body-text-color: #757575; --body-font: "Raleway", sans-serif; --heading-font: "Prata", Georgia, serif;
        }
        
        /* --- Layout cơ bản --- */
        body {
            font-family: var(--body-font); background-color: var(--light-color); color: var(--body-text-color);
            line-height: 1.6; margin: 0; display: flex; min-height: 100vh;
        }
        .admin-sidebar {
            flex: 0 0 260px; background-color: #ffffff; box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 25px; display: flex; flex-direction: column;
        }
        .admin-main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        
        /* --- Sidebar styles --- */
        .admin-sidebar .logo {
            font-family: var(--heading-font); font-size: 1.8em; color: var(--dark-color); text-align: center;
            margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; text-decoration: none;
        }
        .admin-nav ul { list-style: none; padding: 0; margin: 0; }
        .admin-nav a {
            display: block; padding: 14px 18px; text-decoration: none; color: #555; border-radius: 8px;
            margin-bottom: 5px; transition: background-color 0.3s, color 0.3s; font-weight: 500;
        }
        .admin-nav a:hover { background-color: #f7f3f0; }
        .admin-nav a.active { background-color: var(--accent-color); color: #fff; font-weight: 600; }
        .admin-nav a i { margin-right: 12px; width: 20px; }
        .admin-sidebar .user-info { margin-top: auto; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.9em; }

        /* --- Dashboard Styles --- */
        .admin-main-content h1 {
             font-family: var(--heading-font); color: #333; border-bottom: 2px solid var(--accent-color);
             padding-bottom: 10px; margin-top: 0; margin-bottom: 30px;
        }

        /* Grid Thống kê */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease;
            border-left: 4px solid var(--accent-color);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-info h3 {
            font-size: 2.5em;
            margin: 0;
            color: var(--dark-color);
            font-family: var(--heading-font);
            line-height: 1;
        }

        .stat-info p {
            margin: 5px 0 0 0;
            color: #777;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-icon {
            font-size: 3em;
            color: #e0d6ce; /* Màu nhạt của accent */
        }
        
        /* Thẻ đặc biệt cho Pending Requests */
        .stat-card.pending {
            border-left-color: #dc3545;
        }
        .stat-card.pending .stat-icon {
            color: #f5c6cb;
        }
        .stat-card.pending .stat-info h3 {
            color: #dc3545;
        }

        /* Welcome Banner */
        .welcome-banner {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .welcome-banner h2 { margin-top: 0; font-family: var(--heading-font); }
        
        .quick-actions {
            margin-top: 20px;
        }
        .btn-quick {
            display: inline-block; padding: 10px 20px; background-color: var(--accent-color);
            color: white; text-decoration: none; border-radius: 6px; font-weight: 600;
            margin-right: 10px; transition: background 0.3s;
        }
        .btn-quick:hover { background-color: #b3927b; }

    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="/admin/dashboard" class="logo">Admin Panel</a>
        <nav class="admin-nav">
            <ul>
                <li>
                <a href="/" class="home-link">
                    <i class="fas fa-home"></i> Về Trang chủ
                </a>
                 </li>
                <li><a href="/admin/dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a></li>
                <li><a href="/admin/users"><i class="fas fa-users"></i> Quản lý User</a></li>
                <li><a href="/admin/books"><i class="fas fa-book"></i> Quản lý Sách</a></li>
                <li><a href="/admin/categories"><i class="fas fa-tags"></i> Quản lý Thể loại</a></li>
                <li><a href="/admin/author-requests"><i class="fas fa-user-check"></i> Duyệt Tác giả</a></li>
            </ul>
        </nav>
        <div class="user-info">
             Đăng nhập với: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong><br>
            <a href="/auth/logout" style="color: var(--accent-color);">Đăng xuất</a>
        </div>
    </aside>

    <main class="admin-main-content">
        
        <div class="welcome-banner">
            <h2>Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Quản trị viên') ?>!</h2>
            <p>Chào mừng bạn quay trở lại hệ thống quản trị ReadBook Online. Dưới đây là tổng quan về tình hình hoạt động của website.</p>
        </div>

        <h1>Tổng quan</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?= number_format($stats['total_books']) ?></h3>
                    <p>Đầu sách</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3><?= number_format($stats['total_users']) ?></h3>
                    <p>Người dùng</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3><?= number_format($stats['total_categories']) ?></h3>
                    <p>Thể loại</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>

            <div class="stat-card <?= $stats['pending_authors'] > 0 ? 'pending' : '' ?>">
                <div class="stat-info">
                    <h3><?= number_format($stats['pending_authors']) ?></h3>
                    <p>Chờ duyệt</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="quick-actions-area">
            <h3>Thao tác nhanh</h3>
            <div class="quick-actions">
                <a href="/admin/author-requests" class="btn-quick"><i class="fas fa-check-circle"></i> Duyệt tác giả</a>
                <a href="/admin/books" class="btn-quick"><i class="fas fa-list"></i> Xem danh sách sách</a>
                <a href="/admin/categories" class="btn-quick"><i class="fas fa-plus"></i> Thêm thể loại</a>
            </div>
        </div>

    </main>

</body>
</html>