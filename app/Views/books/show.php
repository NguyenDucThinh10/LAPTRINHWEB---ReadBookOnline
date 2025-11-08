<?php 
// Đặt tiêu đề riêng cho trang này, lấy từ tên sách
$pageTitle = htmlspecialchars($book['title']) . " - Chi Tiết Sách";

// Bắt đầu "ghi hình"
ob_start(); 
?>

<!-- Sử dụng section để có khoảng cách trên dưới đồng bộ với trang chủ -->
<section id="book-detail" class="py-5 my-5">
  <div class="container">
    <?php if ($book): ?>
    <div class="row">
      <!-- Cột hiển thị ảnh bìa sách -->
      <div class="col-md-4">
        <figure class="products-thumb">
          <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="...">
            alt="Bìa sách <?php echo htmlspecialchars($book['title']); ?>" class="single-image">
        </figure>
      </div>

      <!-- Cột hiển thị thông tin sách -->
      <div class="col-md-8">
        <div class="product-entry">
          <!-- Tiêu đề sách, dùng class giống trang chủ -->
          <h2 class="section-title divider"><?php echo htmlspecialchars($book['title']); ?></h2>

          <div class="products-content">
            <!-- Tên tác giả -->
            <div class="author-name">Tác giả: <?php echo htmlspecialchars($book['author']); ?></div>

            <!-- Mô tả sách -->
            <div class="item-description my-4">
              <h4>Giới thiệu</h4>
              <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            </div>

            <!-- Nút hành động: Bắt đầu đọc -->
            <?php if (!empty($chapters)): ?>
            <div class="btn-wrap">
              <!-- Link tới trang đọc chương đầu tiên (sẽ làm sau) -->
              <a href="index.php?controller=chapter&action=read&id=<?php echo $chapters[0]['chapter_id']; ?>"
                class="btn btn-outline-accent btn-accent-arrow">
                Đọc từ đầu <i class="icon icon-ns-arrow-right"></i>
              </a>
            </div>
            <?php endif; ?>
                        <!-- NÚT: Thêm vào tủ -->
            <form action="shelf/add" method="POST" class="mt-3">
              <input type="hidden" name="book_id" value="<?= (int)$book['book_id'] ?>">

              <!-- (tùy chọn) chọn trạng thái khi thêm -->
              <div class="d-inline-block me-2">
                <select name="status" class="form-select" style="display:inline-block;width:auto;">
                  <option value="want_to_read">Muốn đọc</option>
                  <option value="reading">Đang đọc</option>
                  <option value="finished">Đã đọc</option>
                </select>
              </div>

              <button type="submit" class="btn btn-outline-accent btn-accent-arrow">
                Thêm vào tủ <i class="icon icon-clipboard"></i>
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>

    <hr class="my-5">

    <!-- Phần hiển thị danh sách chương -->
    <div class="row">
      <div class="col-md-12">
        <div class="section-header">
          <h2 class="section-title">Danh sách chương</h2>
        </div>

        <?php if (!empty($chapters)): ?>
        <ul class="list-group">
          <?php foreach ($chapters as $chapter): ?>
          <!-- Mỗi chương là một item trong danh sách -->
          <!-- Link tới trang đọc của chương đó (sẽ làm sau) -->
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="index.php?controller=chapter&action=read&id=<?php echo $chapter['chapter_id']; ?>">
              Chương <?php echo $chapter['chapter_number']; ?>: <?php echo htmlspecialchars($chapter['title']); ?>
            </a>
            <i class="icon icon-arrow-right"></i>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Cuốn sách này hiện chưa có chương nào.</p>
        <?php endif; ?>
      </div>
    </div>

    <?php else: ?>
    <!-- Trường hợp không tìm thấy sách với ID tương ứng -->
    <div class="row">
      <div class="col-md-12 text-center">
        <h2 class="section-title">Không tìm thấy sách</h2>
        <p>Sách bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
        <div class="btn-wrap mt-4">
          <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-accent btn-accent-arrow">
            Quay về trang chủ <i class="icon icon-home"></i>
          </a>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php
// Dừng "ghi hình" và lấy nội dung
$content = ob_get_clean();

// Gọi file layout
require_once '../app/Views/layouts/app.php';
?>