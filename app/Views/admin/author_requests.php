<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt yêu cầu Tác giả</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            flex: 0 0 260px;
            background-color: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 25px;
            display: flex;
            flex-direction: column;
        }
        .admin-main-content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
        }
        .admin-sidebar .logo {
            font-family: var(--heading-font);
            font-size: 1.8em;
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            text-decoration: none;
        }
        .admin-nav ul { list-style: none; padding: 0; margin: 0; }
        .admin-nav a {
            display: block; padding: 14px 18px; text-decoration: none;
            color: #555; border-radius: 8px; margin-bottom: 5px;
            transition: background-color 0.3s, color 0.3s; font-weight: 500;
        }
        .admin-nav a:hover { background-color: #f7f3f0; }
        .admin-nav a.active {
            background-color: var(--accent-color); color: #fff; font-weight: 600;
        }
        .admin-nav a i { margin-right: 12px; width: 20px; }
        .admin-sidebar .user-info {
            margin-top: auto; padding-top: 20px;
            border-top: 1px solid #eee; font-size: 0.9em;
        }
        .admin-main-content h1 {
             font-family: var(--heading-font); color: #333;
             border-bottom: 2px solid var(--accent-color);
             padding-bottom: 10px; margin-top: 0; margin-bottom: 30px;
        }
        .alert {
            padding: 15px; margin-bottom: 20px; border-radius: 6px;
            border: 1px solid transparent; font-weight: 500;
        }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .data-table {
            width: 100%; border-collapse: collapse; background-color: #fff;
            border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .data-table th, .data-table td {
            padding: 15px; border-bottom: 1px solid #eee; text-align: left;
        }
        .data-table th { background-color: #fafafa; font-weight: 600; color: #333; }
        .data-table tbody tr:hover { background-color: #fdfaf6; }
        .data-table td strong { color: var(--accent-color); }
        .btn {
            padding: 8px 14px; border: none; border-radius: 6px; font-size: 14px;
            cursor: pointer; text-transform: uppercase; font-weight: 600; transition: all 0.3s;
        }
        .btn-approve { background-color: #28a745; color: white; }
        .btn-approve:hover { background-color: #218838; }
        .btn-reject { background-color: #dc3545; color: white; }
        .btn-reject:hover { background-color: #c82333; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="/admin/dashboard" class="logo">Admin Panel</a>
        <nav class="admin-nav">
            <ul>
                <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a></li>
                <li><a href="/admin/users"><i class="fas fa-users"></i> Quản lí người dùng</a></li>
                <li><a href="/admin/books"><i class="fas fa-book"></i> Quản lý Sách</a></li>
                <li><a href="/admin/categories"><i class="fas fa-tags"></i> Quản lý Thể loại</a></li>
                <li><a href="/admin/author-requests" class="active"><i class="fas fa-user-check"></i> Duyệt Tác giả</a></li>
            </ul>
        </nav>
        <div class="user-info">
            Đăng nhập với: 
            <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong><br>
            <a href="/auth/logout" style="color: var(--accent-color);">Đăng xuất</a>
        </div>
    </aside>

    <main class="admin-main-content">
        <h1>Duyệt yêu cầu Tác giả</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'approved'): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Duyệt tác giả thành công!
            </div>
        <?php endif; ?>
         <?php if (isset($_GET['status']) && $_GET['status'] === 'rejected'): ?>
            <div class="alert alert-success" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                <i class="fas fa-check-circle"></i> Đã từ chối yêu cầu.
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra, vui lòng thử lại.
            </div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Bút danh đề xuất</th>
                    <th>Ngày yêu cầu</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Không có yêu cầu nào đang chờ.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?= htmlspecialchars($req['username']) ?></td>
                        <td><?= htmlspecialchars($req['email']) ?></td>
                        <td><strong><?= htmlspecialchars($req['author_name']) ?></strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($req['created_at'])) ?></td>
                        <td>
                            <form action="/admin/approve-author" method="POST" style="margin:0; display: inline-block;">
                                <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $req['user_id'] ?>">
                                <input type="hidden" name="author_name" value="<?= htmlspecialchars($req['author_name']) ?>">
                                
                                <button type="submit" class="btn btn-approve">
                                    <i class="fas fa-check"></i> Duyệt
                                </button>
                            </form> <form action="/admin/reject-author" method="POST" style="margin:0; display: inline-block;">
                                <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                <button type="submit" class="btn btn-reject">
                                    <i class="fas fa-times"></i> Từ chối
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>