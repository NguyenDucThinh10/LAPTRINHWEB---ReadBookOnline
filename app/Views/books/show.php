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
          <img
            src="<?= htmlspecialchars($book['cover_image']) ?>"
            alt="Bìa sách <?= htmlspecialchars($book['title']) ?>"
            class="single-image"
          >
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

<!-- ==========================================================
     ĐÁNH GIÁ & BÌNH LUẬN
     ========================================================== -->
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="row" id="reviews">
  <div class="col-md-12">
    <h3 class="section-title">Đánh giá & Bình Luận</h3>

    <div class="mb-3">
      <strong>Đánh giá trung bình:</strong>
      <?= isset($avg) ? $avg : '0.0' ?>/5
      (<?= isset($total) ? (int)$total : 0 ?> lượt)
    </div>

    <?php if (!empty($_SESSION['user_id'])): ?>
  <div class="card p-3 mb-4">
    <form method="POST"
          action="<?= BASE_URL ?>/public/reviews/<?= !empty($myReview) ? 'update' : 'add' ?>">
      <input type="hidden" name="book_id" value="<?= (int)$book['book_id'] ?>">

      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <label class="col-form-label">Chấm điểm</label>
        </div>
        <div class="col-auto">
          <select name="rating" class="form-select">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?= $i ?>" <?= (!empty($myReview) && (int)$myReview['rating'] === $i) ? 'selected' : ''; ?>>
                <?= $i ?> sao
              </option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <div class="mt-3">
        <textarea name="comment" class="form-control" rows="3" placeholder="Cảm nhận của bạn..."><?= !empty($myReview['comment']) ? htmlspecialchars($myReview['comment']) : '' ?></textarea>
      </div>

      <div class="mt-3 d-flex gap-2">
        <!-- Nút GỬI / CẬP NHẬT -->
        <button type="submit" class="btn btn-primary">
          <?= !empty($myReview) ? 'Cập nhật' : 'Gửi đánh giá' ?>
        </button>

        <?php if (!empty($myReview)): ?>
          <!-- ✅ Nút XÓA GỌI ĐÚNG /public/reviews/delete -->
          <button type="submit"
                  class="btn btn-danger"
                  formaction="<?= BASE_URL ?>/public/reviews/delete"
                  formmethod="POST"
                  onclick="return confirm('Bạn chắc chắn muốn xóa đánh giá này?');">
            XÓA
          </button>
        <?php endif; ?>
      </div>
    </form>
  </div>
<?php else: ?>

      <div class="alert alert-warning">
        <a href="auth/login?next=<?= urlencode($_SERVER['REQUEST_URI'] ?? ('book/detail?id=' . $book['book_id'])) ?>">
          Đăng nhập
        </a> để viết đánh giá.
      </div>

    <?php endif; ?>

    <!-- Danh sách đánh giá -->
    <?php if (!empty($reviews)): ?>
      <?php foreach ($reviews as $rv): ?>
        <div class="border-bottom py-3">
          <div>
            <strong><?= htmlspecialchars($rv['username']) ?></strong> · <?= (int)$rv['rating'] ?>/5 sao
          </div>
          <?php if (!empty($rv['comment'])): ?>
            <div><?= nl2br(htmlspecialchars($rv['comment'])) ?></div>
          <?php endif; ?>
          <small class="text-muted"><?= htmlspecialchars($rv['created_at']) ?></small>
        </div>
      <?php endforeach; ?>

      <?php
        $pages = (int)ceil($total / $limit);
        if ($pages > 1):
      ?>
        <nav class="mt-3">
          <ul class="pagination">
            <?php for ($p = 1; $p <= $pages; $p++): ?>
              <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                <a class="page-link"
                   href="<?= BASE_URL ?>/book/detail?id=<?= (int)$book['book_id'] ?>&page=<?= $p ?>#reviews">
                  <?= $p ?>
                </a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>
    <?php else: ?>
      <p class="text-muted">Chưa có đánh giá nào.</p>
    <?php endif; ?>
  </div>
</div>
<!-- =============== HẾT KHỐI ĐÁNH GIÁ & BÌNH LUẬN =============== -->

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