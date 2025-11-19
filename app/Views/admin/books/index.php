<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sách</title>
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
        .data-table {
            width: 100%; border-collapse: collapse; background-color: #fff;
            border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .data-table th, .data-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        .data-table th { background-color: #fafafa; font-weight: 600; color: #333; }
        .data-table tbody tr:hover { background-color: #fdfaf6; }
        .data-table img.book-cover { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; }
        .action-form { display: inline-block; margin-right: 5px; }
        .btn {
            border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;
            font-weight: 600; font-family: var(--body-font); text-decoration: none; display: inline-block;
        }
        .btn-edit { background-color: var(--accent-color); color: white; }
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
                <li><a href="/admin/books" class="active"><i class="fas fa-book"></i> Quản lý Sách</a></li>
                <li><a href="/admin/categories" ><i class="fas fa-tags"></i> Quản lý Thể loại</a></li>
                <li><a href="/admin/author-requests"><i class="fas fa-user-check"></i> Duyệt Tác giả</a></li>
            </ul>
        </nav>
        <div class="user-info">
             Đăng nhập với: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong><br>
            <a href="/auth/logout" style="color: var(--accent-color);">Đăng xuất</a>
        </div>
    </aside>

    <main class="admin-main-content">
        <h1>Quản lý Sách</h1>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] == 'book_updated') echo 'Cập nhật sách thành công!';
                if ($_GET['status'] == 'book_deleted') echo 'Xóa sách thành công!';
                ?>
            </div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Bìa sách</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th style="width: 200px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kiểm tra xem biến $books có tồn tại và có phải mảng không
                if (isset($books) && is_array($books) && !empty($books)):
                ?>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Bìa sách" class="book-cover"></td>
                        <td><strong><?= htmlspecialchars($book['title']) ?></strong></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td>
                            <a href="/admin/books/edit?id=<?= $book['book_id'] ?>" class="btn btn-edit">Sửa</a>
                            
                            <form action="/admin/books/delete" method="POST" class="action-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này? Mọi chương cũng sẽ bị xóa vĩnh viễn!');">
                                <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                <button type="submit" class="btn btn-delete">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Chưa có sách nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

</body>
</html>