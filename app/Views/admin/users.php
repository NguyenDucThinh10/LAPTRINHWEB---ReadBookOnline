<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --accent-color: #C5A992; --light-color: #F3F2EC; --dark-color: #2f2f2f;
            --body-text-color: #757575; --body-font: "Raleway", sans-serif; --heading-font: "Prata", Georgia, serif;
        }
        body {
            font-family: var(--body-font); background-color: var(--light-color); color: var(--body-text-color);
            line-height: 1.6; margin: 0; display: flex; min-height: 100vh;
        }
        .admin-sidebar {
            flex: 0 0 260px; background-color: #ffffff; box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 25px; display: flex; flex-direction: column;
        }
        .admin-main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
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
        .admin-main-content h1 {
             font-family: var(--heading-font); color: #333; border-bottom: 2px solid var(--accent-color);
             padding-bottom: 10px; margin-top: 0; margin-bottom: 30px;
        }
        /* Thông báo */
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; border: 1px solid transparent; font-weight: 500; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        /* Bảng */
        .data-table {
            width: 100%; border-collapse: collapse; background-color: #fff;
            border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .data-table th, .data-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        .data-table th { background-color: #fafafa; font-weight: 600; color: #333; }
        .data-table tbody tr:hover { background-color: #fdfaf6; }
        /* Form */
        .action-form { display: inline-block; margin-right: 5px; }
        .role-select { padding: 8px; border-radius: 6px; border: 1px solid #ccc; font-family: var(--body-font); }
        .btn {
            border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;
            font-weight: 600; font-family: var(--body-font);
        }
        .btn-save { background-color: var(--accent-color); color: white; }
        .btn-delete { background-color: #dc3545; color: white; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="/admin/dashboard" class="logo">Admin Panel</a>
        <nav class="admin-nav">
            <ul>
                <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a></li>
                <li><a href="/admin/users" class="active"><i class="fas fa-users"></i> Quản lí người dùng</a></li>
                <li><a href="/admin/books"><i class="fas fa-book"></i> Quản lý Sách</a></li>
                <li><a href="/admin/categories" class="active"><i class="fas fa-tags"></i> Quản lý Thể loại</a></li>               
                <li><a href="/admin/author-requests"><i class="fas fa-user-check"></i> Duyệt Tác giả</a></li>
            </ul>
        </nav>
        <div class="user-info">
             Đăng nhập với: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong><br>
            <a href="/auth/logout" style="color: var(--accent-color);">Đăng xuất</a>
        </div>
    </aside>

    <main class="admin-main-content">
        <h1>Quản lý Người dùng</h1>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'role_updated') echo 'Cập nhật vai trò thành công!';
                if ($_GET['status'] == 'user_deleted') echo 'Xóa người dùng thành công!';
                ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                if ($_GET['error'] == 'cannot_delete_self') echo 'Lỗi: Bạn không thể tự xóa chính mình!';
                if ($_GET['error'] == 'cannot_demote_self') echo 'Lỗi: Bạn không thể tự hạ vai trò của mình!';
                ?>
            </div>
        <?php endif; ?>


        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Bút danh</th>
                    <th>Vai trò</th>
                    <th style="width: 300px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['author_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form action="/admin/users/update-role" method="POST" class="action-form">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <select name="new_role" class="role-select">
                                <option value="reader" <?= $user['role'] == 'reader' ? 'selected' : '' ?>>Reader</option>
                                <option value="author" <?= $user['role'] == 'author' ? 'selected' : '' ?>>Author</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-save">Lưu</button>
                        </form>
                        
                        <form action="/admin/users/delete" method="POST" class="action-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác!');">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" class="btn btn-delete">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

</body>
</html>