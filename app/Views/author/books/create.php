<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sách Mới</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* =================================================================
           Lấy các biến màu CSS
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
            padding: 40px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            border: 1px solid #efebe9;
        }
        h1 {
            font-family: var(--heading-font);
            color: var(--dark-color);
            text-align: center;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 15px;
            margin-top: 0;
            margin-bottom: 30px;
        }
        .form-section {
            margin-bottom: 35px;
        }
        .form-section h2 {
            font-family: var(--heading-font);
            color: #795548; /* Nâu vừa */
            font-size: 1.5em;
            border-bottom: 1px solid #d7ccc8;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #5d4037; /* Nâu đậm */
        }
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #bcaaa4;
            background-color: #fbfbfb;
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box; /* Quan trọng */
            font-family: var(--body-font);
        }
        .form-group input[type="text"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(197, 169, 146, 0.3);
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        /* --- CSS cho Chương (Dynamic) --- */
        #chapters-container .chapter-item {
            background-color: #f7f3f0;
            padding: 20px;
            border-radius: 8px;
            border: 1px dashed #d7ccc8;
            margin-bottom: 20px;
            position: relative;
        }
        .chapter-header {
            font-size: 1.2em;
            font-weight: bold;
            color: #8d6e63;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s;
            font-weight: 600;
            font-family: var(--body-font);
        }
        .btn-primary {
            background-color: var(--accent-color);
            color: white;
        }
        .btn-primary:hover {
            background-color: #b3927b;
        }
        .btn-secondary {
            background-color: #efebe9;
            color: #5d4037;
        }
        .btn-secondary:hover {
            background-color: #d7ccc8;
        }
        .btn-remove {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #e57373;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            line-height: 30px;
            padding: 0;
            font-weight: bold;
        }
        .form-actions {
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-feather-alt"></i> Thêm Sách Mới</h1>
    
    <form action="/author/books/store" method="POST" enctype="multipart/form-data">
        
        <div class="form-section">
            <h2>Thông tin cơ bản</h2>
            <div class="form-group">
                <label for="title">Tên sách</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Thể loại</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Chọn thể loại --</option>
                    <?php if (isset($categories) && is_array($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="cover_image">Ảnh bìa</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>
        </div>

        <div class="form-section">
            <h2>Nội dung các chương</h2>
            <div id="chapters-container">
            </div>
            <button type="button" id="add-chapter-btn" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Thêm chương mới
            </button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Đăng sách
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chaptersContainer = document.getElementById('chapters-container');
        const addChapterBtn = document.getElementById('add-chapter-btn');
        let chapterIndex = 0; // Biến đếm để tạo tên (name) duy nhất

        function addChapter() {
            // Tạo một khối div mới cho chương
            const chapterItem = document.createElement('div');
            chapterItem.classList.add('chapter-item');
            
            // Số thứ tự hiển thị cho người dùng (bắt đầu từ 1)
            const displayIndex = chaptersContainer.children.length + 1;

            chapterItem.innerHTML = `
                <div class="chapter-header">Chương ${displayIndex}</div>
                <button type="button" class="btn btn-remove" title="Xóa chương này"><i class="fas fa-times"></i></button>
                
                <div class="form-group">
                    <label for="chapter_title_${chapterIndex}">Tên chương</label>
                    <input type="text" id="chapter_title_${chapterIndex}" 
                           name="chapters[${chapterIndex}][title]" required>
                </div>
                
                <div class="form-group">
                    <label for="chapter_content_${chapterIndex}">Nội dung chương</label>
                    <textarea id="chapter_content_${chapterIndex}" 
                              name="chapters[${chapterIndex}][content]" required></textarea>
                </div>
            `;
            
            chaptersContainer.appendChild(chapterItem);
            
            // Gắn sự kiện xóa cho nút remove
            chapterItem.querySelector('.btn-remove').addEventListener('click', function() {
                chapterItem.remove();
                updateChapterNumbers(); // Cập nhật lại số thứ tự
            });

            chapterIndex++; // Tăng biến đếm chính
        }
        
        // Hàm cập nhật lại số thứ tự "Chương 1", "Chương 2"...
        function updateChapterNumbers() {
            const allChapters = chaptersContainer.querySelectorAll('.chapter-item');
            allChapters.forEach((chapter, index) => {
                chapter.querySelector('.chapter-header').textContent = `Chương ${index + 1}`;
            });
        }

        addChapterBtn.addEventListener('click', addChapter);

        // Tự động thêm chương đầu tiên khi tải trang
        addChapter();
    });
</script>

</body>
</html>