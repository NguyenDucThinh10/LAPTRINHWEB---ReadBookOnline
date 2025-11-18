<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Tác giả</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* =================================================================
           Lấy các biến màu CSS từ file style.css
           ================================================================= */
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
            padding: 40px; /* Thêm padding cho body */
        }

        /* =================================================================
           BỐ CỤC CHÍNH (Container)
           ================================================================= */
        .author-container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.07);
        }

        /* --- Tiêu đề và Nút hành động --- */
        .author-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .author-header h1 {
            font-family: var(--heading-font);
            color: #333;
            margin: 0;
            font-size: 2.2em;
        }
        
        /* ✅ Thêm 1 div để bọc các nút */
        .header-actions {
            display: flex;
            gap: 10px; /* Khoảng cách giữa các nút */
        }

        /* Nút "Thêm sách mới" - Chức năng Upload */
        .btn-primary {
            background-color: var(--accent-color);
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-family: var(--body-font);
            display: inline-block;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #b3927b; /* Màu đậm hơn */
        }
        .btn-primary i {
            margin-right: 8px;
        }
        
        /* ✅ CSS CHO NÚT MỚI */
        .btn-secondary {
            background-color: #f7f3f0; /* Màu nền be nhạt */
            color: var(--body-text-color);
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-family: var(--body-font);
            display: inline-block;
            transition: background-color 0.3s;
            border: 1px solid #d7ccc8; /* Thêm viền mỏng */
        }
        .btn-secondary:hover {
            background-color: #efebe9; /* Đậm hơn khi hover */
        }
        .btn-secondary i {
            margin-right: 8px;
        }


        /* --- Bảng danh sách sách --- */
        .author-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .author-table th,
        .author-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle; /* Căn giữa theo chiều dọc */
        }
        .author-table th {
            background-color: #fafafa;
            font-weight: 600;
            color: #333;
        }
        .author-table tbody tr:hover {
            background-color: #fdfaf6; /* Nền nâu nhạt khi hover */
        }
        .author-table img.book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        .author-table .actions a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            margin-right: 10px;
        }
        .author-table .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="author-container">
        
        <div class="author-header">
            <h1>Sách của tôi</h1>
            
            <div class="header-actions">
                <a href="/" class="btn-secondary">
                    <i class="fas fa-home"></i> Về Trang chủ
                </a>
                
                <a href="/author/books/create" class="btn-primary">
                    <i class="fas fa-plus"></i> Thêm sách mới
                </a>
            </div>
            </div>

        <h2>Bảng sách</h2>
        
        <table class="author-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Bìa sách</th>
                    <th>Tên sách</th>
                    <th>Ngày đăng</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kiểm tra xem biến $books có tồn tại và có phải là mảng không
                if (isset($books) && is_array($books) && !empty($books)):
                ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Bìa sách" class="book-cover">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($book['title']) ?></strong>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($book['created_at'])) ?>
                            </td>
                            <td class="actions">
                                <span>—</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px; font-weight: 500;">
                            Bạn chưa đăng cuốn sách nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
    </div>

</body>
</html>