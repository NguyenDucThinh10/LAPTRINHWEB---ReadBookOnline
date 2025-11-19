<!-- Giao diện đọc sách (PDF/EPUB viewer) -->
<?php 
$pageTitle = htmlspecialchars($chapter['title']) . " - Đọc Sách";
ob_start(); 
?>

<style>
/* CSS nội bộ để trang đọc sách đẹp hơn, bạn có thể chuyển vào file style.css sau */
.reading-container {
  max-width: 800px;
  margin: auto;
}

.chapter-nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.chapter-content {
  font-family: "Times New Roman", serif;
  font-size: 1.2rem;
  line-height: 1.8;
  text-align: justify;
}

.nav-button {
  display: inline-block;
  padding: 10px 20px;
  border: 1px solid #ccc;
  text-decoration: none;
  color: #333;
}

.nav-button.disabled {
  color: #ccc;
  pointer-events: none;
  background-color: #f7f7f7;
}
</style>

<section id="reading-page" class="py-5 my-5">
  <div class="container reading-container">

    <!-- Thanh điều hướng đầu trang -->
    <div class="chapter-nav">
      <!-- SỬA LẠI LINK CHƯƠNG TRƯỚC -->
      <?php if ($prevChapter): ?>
      <a href="<?php echo BASE_URL; ?>/chapter/read/<?php echo $prevChapter['chapter_id']; ?>" class="nav-button">&lt;
        Chương Trước</a>
      <?php else: ?>
      <span class="nav-button disabled">&lt; Chương Trước</span>
      <?php endif; ?>

      <!-- SỬA LẠI LINK MỤC LỤC -->
      <a href="<?php echo BASE_URL; ?>/book/detail/<?php echo $chapter['book_id']; ?>" class="nav-button">Mục Lục</a>

      <!-- SỬA LẠI LINK CHƯƠNG SAU -->
      <?php if ($nextChapter): ?>
      <a href="<?php echo BASE_URL; ?>/chapter/read/<?php echo $nextChapter['chapter_id']; ?>" class="nav-button">Chương
        Sau &gt;</a>
      <?php else: ?>
      <span class="nav-button disabled">Chương Sau &gt;</span>
      <?php endif; ?>
    </div>

    <!-- Nội dung chính của chương -->
    <h2 class="section-title divider text-center"><?php echo htmlspecialchars($chapter['title']); ?></h2>
    <div class="chapter-content mt-5">
      <?php echo nl2br(htmlspecialchars($chapter['content'])); ?>
    </div>

    <!-- Thanh điều hướng cuối trang (cũng được sửa lại) -->
    <hr class="my-5">
    <div class="chapter-nav">
      <?php if ($prevChapter): ?>
      <a href="<?php echo BASE_URL; ?>/chapter/read/<?php echo $prevChapter['chapter_id']; ?>" class="nav-button">&lt;
        Chương Trước</a>
      <?php else: ?>
      <span class="nav-button disabled">&lt; Chương Trước</span>
      <?php endif; ?>

      <a href="<?php echo BASE_URL; ?>/book/detail/<?php echo $chapter['book_id']; ?>" class="nav-button">Mục Lục</a>

      <?php if ($nextChapter): ?>
      <a href="<?php echo BASE_URL; ?>/chapter/read/<?php echo $nextChapter['chapter_id']; ?>" class="nav-button">Chương
        Sau &gt;</a>
      <?php else: ?>
      <span class="nav-button disabled">Chương Sau &gt;</span>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php
$content = ob_get_clean();
require_once '../app/Views/layouts/app.php';
?>