<?php 
// 1. Đặt tiêu đề riêng cho trang này
$pageTitle = "Trang Chủ - Thư Viện Sách";

// 2. Bắt đầu "ghi hình" lại tất cả HTML bên dưới
ob_start(); 
?>
<section id="billboard">

  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <button class="prev slick-arrow">
          <i class="icon icon-arrow-left"></i>
        </button>

        <div class="main-slider pattern-overlay">
          <div class="slider-item">
            <div class="banner-content">
              <h2 class="banner-title">Life of the Wild</h2>
              <p>“Đọc sách là một công cụ nền tảng để sống một cuộc đời tốt đẹp. Thông qua sách, chúng ta có thể học hỏi
                từ trải nghiệm của người khác, làm giàu thêm sự hiểu biết về thế giới và nuôi dưỡng thói quen suy ngẫm
                cùng sự thấu suốt. Một cuốn sách hay không chỉ đơn thuần cung cấp thông tin; nó còn mời người đọc bước
                vào một cuộc đối thoại – cuộc đối thoại ấy vẫn tiếp tục định hình tư duy của ta rất lâu sau khi trang
                cuối cùng đã được lật qua.”</p>
              <div class="btn-wrap">
                <a href="#" class="btn btn-outline-accent btn-accent-arrow">Read More<i
                    class="icon icon-ns-arrow-right"></i></a>
              </div>
            </div>
            <!--banner-content-->
            <img src="<?php echo BASE_URL; ?>/images/main-banner1.jpg" alt="banner" class="banner-image">
          </div>
          <!--slider-item-->
          <div class="slider-item">
            <div class="banner-content">
              <h2 class="banner-title">Birds gonna be Happy</h2>
              <p>"Số lượng sách cứ tiếp tục lớn dần lên và ta có thể dự đoán rằng rồi sẽ đến lúc học được từ sách cũng
                khó như học toàn bộ vũ trụ. Rồi tìm kiếm từng chút sự thật ẩn dấu trong tự nhiên cũng sẽ thuận tiện như
                tìm nó giữa vô vàn chuồng sách.” - Denis Diderot</p>
              <div class="btn-wrap">
                <a href="#" class="btn btn-outline-accent btn-accent-arrow">Read More<i
                    class="icon icon-ns-arrow-right"></i></a>
              </div>
            </div>
            <!--banner-content-->
            <img src="<?php echo BASE_URL; ?>/images/main-banner2.jpg" alt="banner" class="banner-image">

          </div>
          <!--slider-item-->

        </div>
        <!--slider-->

        <button class="next slick-arrow">
          <i class="icon icon-arrow-right"></i>
        </button>

      </div>
    </div>
  </div>

</section>

<section id="client-holder" data-aos="fade-up">
  <div class="container">
    <div class="row">
      <div class="inner-content">
        <div class="logo-wrap">
          <div class="grid">
            <img src="<?php echo BASE_URL; ?>/images/client-image1.png" alt="client">
            <img src="<?php echo BASE_URL; ?>/images/client-image2.png" alt="client">
            <img src="<?php echo BASE_URL; ?>/images/client-image3.png" alt="client">
            <img src="<?php echo BASE_URL; ?>/images/client-image4.png" alt="client">
            <img src="<?php echo BASE_URL; ?>/images/client-image5.png" alt="client">

          </div>
        </div>
        <!--image-holder-->
      </div>
    </div>
  </div>
</section>

<section id="featured-books" class="py-5 my-5">
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <div class="section-header align-center">
          <div class="title">
            <span>Kho sách</span>
          </div>
          <h2 class="section-title">Sách nổi bật</h2>
        </div>

        <div class="product-list" data-aos="fade-up">
          <div class="row">

            <?php foreach ($featuredBooks as $book): ?>
            <div class="col-md-3">
              <div class="product-item">
                <figure class="product-style">

                  <!-- SỬA 1: Dùng đúng tên cột 'cover_image' từ CSDL -->
                  <img src="<?php echo BASE_URL; ?>/<?= htmlspecialchars($book['cover_image']) ?>"
                    alt="Bìa sách <?php echo htmlspecialchars($book['title']); ?>" class="product-item"
                    style="max-width: 300px; height: 400px;">

                  <!-- SỬA 2: Thay nút "Add to cart" bằng nút "Xem chi tiết" -->
                  <!-- Link này sẽ dẫn đến trang chi tiết của sách sau này -->
                  <a href="<?php echo BASE_URL; ?>/book/detail/<?php echo (int)$book['book_id']; ?>"
                    class="add-to-cart">
                    Xem chi tiết
                  </a>
                </figure>

                </figure>
                <figcaption>
                  <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                  <span><?php echo htmlspecialchars($book['author']); ?></span>
                  <div class="item-views" style="color: #888; margin-top: 5px; font-size: 14px;">
                    <i class="icon icon-eye"></i> <?php echo number_format($book['views']); ?> lượt xem
                  </div>
                  <!-- SỬA 3: Xóa phần hiển thị giá vì chúng ta không bán sách -->

                </figcaption>
              </div>
            </div>
            <?php endforeach; ?>

          </div>
        </div>
        <!--grid-->


      </div>
</section>
<section id="popular-books" class="bookshelf py-5 my-5">
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <!-- Header và tabs của section -->
        <div class="section-header align-center">
          <div class="title">
            <span>Khám phá thêm</span>
          </div>
          <h2 class="section-title">Sách mới nhất</h2>
        </div>
        <ul class="tabs">
          <li data-tab-target="#all-books" class="active tab">Tất cả</li>
          <?php foreach ($popularCategories as $category): ?>
          <li class="tab">
            <a href="<?php echo BASE_URL; ?>/category/<?php echo $category['category_id']; ?>"
              style="text-decoration: none; color: inherit;">
              <?php echo htmlspecialchars($category['name']); ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>

        <!-- Nội dung các tab -->
        <div class="tab-content">
          <div id="all-books" data-tab-content class="active">
            <div class="row justify-content-center">
              <?php $displayBooks = array_slice($books, 0, 8); ?>
              <?php foreach ($displayBooks as $book): ?>

              <!-- ======================================================= -->
              <!--    ĐÂY LÀ "KHUÔN VÀNG" ĐÃ ĐƯỢC COPY-PASTE VÀO ĐÂY       -->
              <!-- ======================================================= -->
              <div class="col-md-3">
                <div class="product-item">
                  <figure class="product-style">

                    <!-- Phần ảnh -->
                    <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($book['cover_image']); ?>"
                      alt="Bìa sách <?php echo htmlspecialchars($book['title']); ?>" class="product-item"
                      style="max-width: 300px; height: 400px; object-fit: cover; margin: auto;">

                    <!-- Nút "Xem chi tiết" -->
                    <a href="<?php echo BASE_URL; ?>/book/detail/<?php echo (int)$book['book_id']; ?>"
                      class="add-to-cart">
                      Xem chi tiết
                    </a>
                  </figure>

                  <!-- Phần text -->
                  <figcaption>
                    <h3><a href="<?php echo BASE_URL; ?>/book/detail/<?php echo (int)$book['book_id']; ?>"
                        style="color: inherit; text-decoration: none;"><?php echo htmlspecialchars($book['title']); ?></a>
                    </h3>
                    <span><?php echo htmlspecialchars($book['author']); ?></span>

                    <!-- Sách mới nhất có thể không cần hiển thị lượt xem, nhưng ta cứ để lại 1 khoảng trống để giữ chiều cao đồng bộ -->
                    <div class="item-views" style="color: #888; margin-top: 5px; font-size: 14px; min-height: 24px;">
                      &nbsp;
                      <!-- Thêm khoảng trắng không ngắt dòng -->
                    </div>
                  </figcaption>
                </div>
              </div>
              <!-- ======================================================= -->
              <!--                KẾT THÚC "KHUÔN VÀNG"                   -->
              <!-- ======================================================= -->

              <?php endforeach; ?>
            </div>
          </div>
          <!-- Các tab-content khác có thể để trống hoặc ẩn đi -->
        </div>

      </div>
    </div>
  </div>
</section>

<section id="quotation" class="align-center pb-5 mb-5">
  <div class="inner-content">
    <h2 class="section-title divider">Câu nói hay trong ngày</h2>
    <blockquote data-aos="fade-up">
      <q>Theo tâm lý học, giữ im lặng không có nghĩa là bạn không có gì để nói, mà là bạn cho rằng họ chưa sẵn sàng để
        nghe suy nghĩ của bạn. - Khuyết Danh</q>
      <div class="author-name">Dr. Seuss</div>
    </blockquote>
  </div>
</section>

<section id="latest-blog" class="py-5 my-5">
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <div class="section-header align-center">
          <div class="title">
            <span>Báo của chúng tôi</span>
          </div>
          <h2 class="section-title">Báo mới nhất</h2>
        </div>

        <div class="row">

          <div class="col-md-4">

            <article class="column" data-aos="fade-up">

              <figure>
                <a href="#" class="image-hvr-effect">
                  <img src="<?php echo BASE_URL; ?>/images/post-img1.jpg" alt="post" class="post-image">
                </a>
              </figure>

              <div class="post-item">
                <div class="meta-date">Mar 30, 2021</div>
                <h3><a href="#">Reading books always makes the moments happy</a></h3>

                <div class="links-element">
                  <div class="categories">inspiration</div>
                  <div class="social-links">
                    <ul>
                      <li>
                        <a href="#"><i class="icon icon-facebook"></i></a>
                      </li>
                      <li>
                        <a href="#"><i class="icon icon-twitter"></i></a>
                      </li>
                      <li>
                        <a href="#"><i class="icon icon-behance-square"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>
                <!--links-element-->

              </div>
            </article>

          </div>
          <div class="col-md-4">

            <article class="column" data-aos="fade-up" data-aos-delay="200">
              <figure>
                <a href="#" class="image-hvr-effect">
                  <img src="<?php echo BASE_URL; ?>/images/post-img2.jpg" alt="post" class="post-image">
                </a>
              </figure>
              <div class="post-item">
                <div class="meta-date">Mar 29, 2021</div>
                <h3><a href="#">Reading books always makes the moments happy</a></h3>

                <div class="links-element">
                  <div class="categories">inspiration</div>
                  <div class="social-links">
                    <ul>
                      <li>
                        <a href="#"><i class="icon icon-facebook"></i></a>
                      </li>
                      <li>
                        <a href="#"><i class="icon icon-twitter"></i></a>
                      </li>
                      <li>
                        <a href="#"><i class="icon icon-behance-square"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>
                <!--links-element-->

              </div>
            </article>

          </div>
          <div class="col-md-4">

            <article class="column" data-aos="fade-up" data-aos-delay="400">
              <figure>
                <a href="#" class="image-hvr-effect">
                  <img src="<?php echo BASE_URL; ?>/images/post-img3.jpg" alt="post" class="post-image">
                </a>
              </figure>
              <div class="post-item">
                <div class="meta-date">Feb 27, 2021</div>
                <h3><a href="#">Reading books always makes the moments happy</a></h3>

                <div class="links-element">
                  <div class="categories">inspiration</div>
                  <div class="social-links">
                    <ul>
                      <li>
                        <a href="#"><i class="icon icon-facebook"></i></a>
                      </li>
                      <li>
                        <a href="#"><i class="icon icon-twitter"></i></a>
                      </li>
                      <li>
                        <a href="#"><i class="icon icon-behance-square"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>
                <!--links-element-->

              </div>
            </article>

          </div>

        </div>

        <div class="row">

          <div class="btn-wrap align-center">
            <a href="#" class="btn btn-outline-accent btn-accent-arrow" tabindex="0">Read All Articles<i
                class="icon icon-ns-arrow-right"></i></a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php
// 3. Dừng "ghi hình" và lấy tất cả nội dung đã ghi vào biến $content
$content = ob_get_clean();

// 4. Cuối cùng, gọi file layout (lát bánh mì) để nó bọc lấy $content (phần nhân)
require_once '../app/Views/layouts/app.php';
?>