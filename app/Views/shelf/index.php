<?php
// File: app/Views/shelf/index.php
// (PHIÊN BẢN CŨ CỦA BẠN - TỰ NẠP LAYOUT)

// 1. Lấy $flash (Vì Controller không còn truyền qua view() nữa)
if (session_status()===PHP_SESSION_NONE) session_start();
$pageTitle = "Tủ sách của tôi";
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);

// 2. Bắt đầu gom HTML
ob_start(); 
?>

<section class="section-shelf py-5 my-5">
  <div class="container">
    <div class="section-header text-center mb-4">
      <h2 class="section-title divider">Tủ sách của tôi</h2>
      <p class="muted-para">Lưu lại những cuốn sách bạn yêu thích để đọc sau.</p>
    </div>

    <?php if (!empty($flash)): ?>
    <div class="alert accent-alert"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="shelf-filter mb-4">

      <a class="filter-pill <?= empty($_GET['status']) ? 'active' : '' ?>" href="<?php echo BASE_URL; ?>/shelf">Tất
        cả</a>
      <a class="filter-pill <?= (($_GET['status'] ?? '')==='want_to_read') ? 'active' : '' ?>"
        href="<?php echo BASE_URL; ?>/shelf?status=want_to_read">Muốn đọc</a>
      <a class="filter-pill <?= (($_GET['status'] ?? '')==='reading') ? 'active' : '' ?>"
        href="<?php echo BASE_URL; ?>/shelf?status=reading">Đang đọc</a>
      <a class="filter-pill <?= (($_GET['status'] ?? '')==='finished') ? 'active' : '' ?>"
        href="<?php echo BASE_URL; ?>/shelf?status=finished">Đã đọc</a>


    </div>

    <?php if (empty($items)): ?>
    <div class="empty-wrap">
      <div class="empty-card">
        <div class="empty-illus">📚</div>
        <h3>Chưa có sách nào trong tủ</h3>
        <p class="muted-para">Khám phá các đầu sách ở trang chủ và thêm vào tủ để theo dõi.</p>
        <a class="btn btn-outline-accent btn-accent-arrow" href="<?= BASE_URL ?>/public/">
          Về trang chủ <i class="icon icon-ns-arrow-right"></i>
        </a>


      </div>
    </div>
    <?php else: ?>

    <div class="shelf-grid">
      <?php foreach ($items as $it): ?>
      <article class="book-card">
        <a class="thumb" href="<?php echo BASE_URL; ?>/book/detail/<?php echo (int)$it['book_id']; ?>">
          <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($it['cover_image'] ?? ''); ?>" alt=""
            loading="lazy">
        </a>
        <div class="meta">
          <h3 class="title">
            <a href="<?php echo BASE_URL; ?>/book/detail/<?php echo (int)$it['book_id']; ?>">
              <?= htmlspecialchars($it['title']) ?>
            </a>
          </h3>
          <div class="author">by <?= htmlspecialchars($it['author']) ?></div>
          <div class="status-line">
            <span class="badge status-<?= htmlspecialchars($it['status']) ?>">
              <?= $it['status']==='want_to_read'?'Muốn đọc':($it['status']==='reading'?'Đang đọc':'Đã đọc') ?>
            </span>
          </div>
        </div>

        <div class="actions">
          <form action="<?php echo BASE_URL; ?>/shelf/status" method="POST" class="inline">

            <input type="hidden" name="book_id" value="<?= (int)$it['book_id'] ?>">
            <select name="status" class="status-select">
              <option value="want_to_read" <?= $it['status']==='want_to_read'?'selected':''; ?>>Muốn đọc</option>
              <option value="reading" <?= $it['status']==='reading'?'selected':''; ?>>Đang đọc</option>
              <option value="finished" <?= $it['status']==='finished'?'selected':''; ?>>Đã đọc</option>
            </select>
            <button type="submit" class="btn tiny">Lưu</button>
          </form>

          <form action="<?php echo BASE_URL; ?>/shelf/remove" method="POST" class="inline">

            <input type="hidden" name="book_id" value="<?= (int)$it['book_id'] ?>">
            <button type="submit" class="btn tiny ghost">Xoá</button>
          </form>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>
  </div>
</section>

<?php

$content = ob_get_clean();                         // lấy HTML vào $content
require_once __DIR__ . '/../layouts/app.php';      // nạp layout (kéo CSS/JS)

