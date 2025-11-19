<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thể loại</title>
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
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; border: 1px solid transparent; font-weight: 500; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        
        /* Bố cục 2 cột */
        .page-layout { display: flex; gap: 30px; }
        .form-column { flex: 1; min-width: 300px; }
        .table-column { flex: 2; }
        
        /* Form */
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); }
        .form-container h2 { font-family: var(--heading-font); margin-top: 0; color: #795548; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #5d4037; }
        .form-group input[type="text"], .form-group textarea {
            width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #bcaaa4;
            background-color: #fbfbfb; box-sizing: border-box; font-family: var(--body-font);
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        
        /* Bảng */
        .data-table {
            width: 100%; border-collapse: collapse; background-color: #fff;
            border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .data-table th, .data-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        .data-table th { background-color: #fafafa; font-weight: 600; color: #333; }
        .data-table td p { margin: 0; font-size: 0.9em; color: #777; }
        .data-table td .actions { display: flex; gap: 10px; }
        .btn {
            border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;
            font-weight: 600; font-family: var(--body-font); text-decoration: none; display: inline-block;
        }
        .btn-primary { background-color: var(--accent-color); color: white; }
        .btn-edit { background-color: #17a2b8; color: white; }
        .btn-delete { background-color: #dc3545; color: white; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="/admin/dashboard" class="logo">Admin Panel</a>
        <nav class="admin-nav">
             <ul>
                <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a></li>
                <li><a href="/admin/users"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
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
        <h1>Quản lý Thể loại</h1>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'created') echo 'Tạo thể loại mới thành công!';
                if ($_GET['status'] == 'updated') echo 'Cập nhật thể loại thành công!';
                if ($_GET['status'] == 'deleted') echo 'Xóa thể loại thành công!';
                ?>
            </div>
        <?php endif; ?>
        
        <div class="page-layout">
            
            <div class="form-column">
                <div class="form-container">
                    <h2>Thêm Thể loại mới</h2>
                    <form action="/admin/categories/store" method="POST">
                        <div class="form-group">
                            <label for="name">Tên thể loại</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea id="description" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
            
            <div class="table-column">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tên thể loại</th>
                            <th>Mô tả</th>
                            <th style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td><p><?= htmlspecialchars($cat['description'] ?? '') ?></p></td>
                            <td class="actions">
                                <a href="/admin/categories/edit?id=<?= $cat['category_id'] ?>" class="btn btn-edit">
                                    <i class="fas fa-pen"></i> Sửa
                                </a>
                                <form action="/admin/categories/delete" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa? Sách thuộc thể loại này sẽ bị set về NULL.');">
                                    <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
                                    <button type="submit" class="btn btn-delete">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div> </main>

</body>
</html>