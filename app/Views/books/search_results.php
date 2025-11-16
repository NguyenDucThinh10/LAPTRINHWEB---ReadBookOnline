<?php 
$pageTitle = "Kết quả tìm kiếm cho: " . htmlspecialchars($keyword);
ob_start(); 
?>

<section id="search-results" class="py-5 my-5">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header align-center">
          <h2 class="section-title">Kết quả tìm kiếm</h2>
          <p>Đã tìm thấy <strong><?php echo count($books); ?></strong> kết quả cho từ khóa
            "<strong><?php echo htmlspecialchars($keyword); ?></strong>"</p>
        </div>

        <?php if (!empty($books)): ?>
        <div class="product-list" data-aos="fade-up">
          <div class="row">
            <?php foreach ($books as $book): ?>
            <div class="col-md-3">
              <div class="product-item">
                <figure class="product-style">
                  <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($book['cover_image']); ?>"
                    alt="Bìa sách" class="product-item" style="height: 400px; object-fit: cover;">
                  <a href="<?php echo BASE_URL; ?>/book/detail/<?php echo $book['book_id']; ?>" class="add-to-cart">Xem
                    chi tiết</a>
                </figure>
                <figcaption>
                  <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                  <span><?php echo htmlspecialchars($book['author']); ?></span>
                </figcaption>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <p class="text-center">Không tìm thấy cuốn sách nào phù hợp.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/app/Views/layouts/app.php';
?>