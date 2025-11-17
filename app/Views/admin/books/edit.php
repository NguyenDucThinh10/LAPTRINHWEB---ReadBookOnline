<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin sách</title>
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
        
        /* CSS cho Form */
        .form-container {
            background-color: #fff; padding: 30px 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            max-width: 800px; /* Giới hạn chiều rộng form */
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; margin-bottom: 8px; font-weight: 600; color: #5d4037; /* Nâu đậm */
        }
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #bcaaa4;
            background-color: #fbfbfb; box-sizing: border-box; font-family: var(--body-font);
            font-size: 1em;
        }
        .form-group textarea { min-height: 150px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none; border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(197, 169, 146, 0.3);
        }
        .form-actions { text-align: right; margin-top: 30px; }
        .btn {
            border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer;
            font-weight: 600; font-family: var(--body-font); text-decoration: none; display: inline-block;
            font-size: 1em;
        }
        .btn-primary { background-color: var(--accent-color); color: white; }
        .btn-primary:hover { background-color: #b3927b; }
        .btn-secondary { background-color: #eee; color: #555; margin-right: 10px; }
        .btn-secondary:hover { background-color: #ddd; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="/admin/dashboard" class="logo">Admin Panel</a>
        <nav class="admin-nav">
             <ul>
                <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a></li>
                <li><a href="/admin/users"><i class="fas fa-users"></i> Quản lý User</a></li>
                <li><a href="/admin/books" class="active"><i class="fas fa-book"></i> Quản lý Sách</a></li>
                <li><a href="/admin/author-requests"><i class="fas fa-user-check"></i> Duyệt Tác giả</a></li>
            </ul>
        </nav>
        <div class="user-info">
             Đăng nhập với: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong><br>
            <a href="/auth/logout" style="color: var(--accent-color);">Đăng xuất</a>
        </div>
    </aside>

    <main class="admin-main-content">
        <h1>Sửa thông tin sách</h1>

        <div class="form-container">
            <form action="/admin/books/update" method="POST">
                
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">

                <div class="form-group">
                    <label for="title">Tên sách</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Thể loại</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Chọn thể loại --</option>
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>" 
                                    <?= ($category['category_id'] == $book['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea id="description" name="description" rows="8" required><?= htmlspecialchars($book['description']) ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="/admin/books" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>