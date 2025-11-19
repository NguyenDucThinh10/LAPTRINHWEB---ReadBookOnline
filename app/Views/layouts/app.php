<!-- ĐIỂM TRUY CẬP DUY NHẤT (Front Controller) -->
<!DOCTYPE html>
<html lang="en">

<head>
  <title>BookSaw </title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">

    <!-- ĐẶT GỐC CHO TÀI NGUYÊN TĨNH: trỏ về /public -->
  <!-- <base href="<?= BASE_URL ?>/"> -->



  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/normalize.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/icomoon/icomoon.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/vendor.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">

  <style>
    
  </style>
</head>
<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $__flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    ?>
<?php if ($__flash): ?>
<div class="container" style="margin-top:16px;">
  <div class="alert accent-alert"><?= htmlspecialchars($__flash) ?></div>
</div>
<?php endif; ?>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

  <div id="header-wrap">

    <div class="top-content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <div class="social-links">
              <ul>
                <li>
                  <a href="#"><i class="icon icon-facebook"></i></a>
                </li>
                <li>
                  <a href="#"><i class="icon icon-twitter"></i></a>
                </li>
                <li>
                  <a href="#"><i class="icon icon-youtube-play"></i></a>
                </li>
                <li>
                  <a href="#"><i class="icon icon-behance-square"></i></a>
                </li>
              </ul>
            </div>
            <!--social-links-->
          </div>
          <div class="col-md-6">
            <div class="right-element">
              <a href="/account" class="user-account for-buy"><i class="icon icon-user"></i><span>Account</span></a>
              <a href="#" class="cart for-buy"><i class="icon icon-clipboard"></i><span>Cart:(0
                  $)</span></a>

              
            </div>
            <!--top-right-->
          </div>

        </div>
      </div>
    </div>
    <!--top-content-->

    <header id="header">
      <div class="container-fluid">
        <div class="row" id="hang">

          <div class="col-md-2">
            <div class="main-logo">
              <a href="/"><img src="<?php echo BASE_URL; ?>/images/main-logo.png" alt="logo"></a>
            </div>

          </div>

          <div class="col-md-10">

            <nav id="navbar">
              <div class="main-menu stellarnav">
                <ul class="menu-list">
                  <li class="menu-item">
     <form action="<?php echo BASE_URL; ?>/search" method="GET" class="search-form-nav"
        style="height:38px">
        <input type=" search" name="q" placeholder="Tìm kiếm tên sách..." class="search-input-nav"
        required>
        <button type="submit" class="search-button-nav" style="height:38px"><i
        class="icon icon-search">tim
        kiem</i></button>
      </form>
      </li>

              
                  <li class="menu-item"><a href="#latest-blog" class="nav-link">Articles</a></li>
                  <li class="menu-item"><a href="/shelf" class="nav-link">Tủ Sách</a></li>
                  <?php
    // --- BẮT ĐẦU LOGIC "ĂN LIỀN" ---
    
    // 1. Gọi các file cần thiết
    // Chúng ta dùng require_once để đảm bảo file chỉ được nạp 1 lần
    require_once ROOT_PATH . '/app/Core/Database.php';
    require_once ROOT_PATH . '/app/Models/Category.php';

    // 2. Kết nối CSDL và lấy dữ liệu
    $dbConnection = App\Core\Database::getConnection();
    $categoryModel = new App\Models\Category($dbConnection);
    $globalCategories = $categoryModel->getAll();

    // --- KẾT THÚC LOGIC "ĂN LIỀN" ---
?>

                  <!-- Menu Thể Loại Động -->
                  <?php if (!empty($globalCategories)): ?>
                  <li class="menu-item has-sub">
                    <a href="#" class="nav-link">Thể Loại</a>
                    <ul>
                      <?php foreach ($globalCategories as $category): ?>
                      <li>
                        <!-- Link sẽ trỏ đến trang danh sách sách của thể loại đó -->
                        <!-- CHÚ Ý: Chúng ta sẽ dùng URL đơn giản để không phải tạo router mới -->
                        <a href="<?php echo BASE_URL; ?>/category/<?php echo $category['category_id']; ?>">
                          <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                      </li>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                  <?php endif; ?>


                </ul>

                <div class="hamburger">
                  <span class="bar"></span>
                  <span class="bar"></span>
                  <span class="bar"></span>
                </div>

              </div>
            </nav>

          </div>

        </div>
      </div>
    </header>

  </div>
  <!--header-wrap-->
  <div id="main">
    <?php echo $content; ?>
  </div>
  <!--main-->

  <!-- FOOTER -->
  <footer id="footer">
    <div class="container">
      <div class="row">

        <div class="col-md-4">

          <div class="footer-item">
            <div class="company-brand">
              <img src="<?php echo BASE_URL; ?>/images/main-logo.png" class="footer-logo" alt="logo"
                class="footer-logo">
              <p><q>Theo tâm lý học, giữ im lặng không có nghĩa là bạn không có gì để nói, mà là bạn cho rằng họ chưa
                  sẵn sàng để
                  nghe suy nghĩ của bạn. - Khuyết Danh</q></p>
            </div>
          </div>

        </div>

        <div class="col-md-2">

          <div class="footer-menu">
            <h5>About Us</h5>
            <ul class="menu-list">
              <li class="menu-item">
                <a href="#">Phiên bản</a>
              </li>
              <li class="menu-item">
                <a href="#">Báo mới </a>
              </li>
              <li class="menu-item">
                <a href="#">Cộng tác viên</a>
              </li>
              <li class="menu-item">
                <a href="#">Điều khoản dịch vụ</a>
              </li>
              <li class="menu-item">
                <a href="#">Ủng hộ chúng tôi</a>
              </li>
            </ul>
          </div>

        </div>
        <div class="col-md-2">

          <div class="footer-menu">
            <h5>Khám phá</h5>
            <ul class="menu-list">
              <li class="menu-item">
                <a href="#">Home</a>
              </li>
              <li class="menu-item">
                <a href="#">Books</a>
              </li>
              <li class="menu-item">
                <a href="#">Tác giả</a>
              </li>
              <li class="menu-item">
                <a href="#">Chủ đề</a>
              </li>
              <li class="menu-item">
                <a href="#">Tìm kiếm nâng cao</a>
              </li>
            </ul>
          </div>

        </div>
        <div class="col-md-2">

          <div class="footer-menu">
            <h5>Tài khoản</h5>
            <ul class="menu-list">
              <li class="menu-item">
                <a href="#">Đăng nhập</a>
              </li>
              <li class="menu-item">
                <a href="#">Xem tủ sách</a>
              </li>
              <li class="menu-item">
                <a href="#">Lịch sử</a>
              </li>
            </ul>
          </div>

        </div>
        <div class="col-md-2">

          <div class="footer-menu">
            <h5>Trợ giúp</h5>
            <ul class="menu-list">
              <li class="menu-item">
                <a href="#">Trung tậm hỗ trợ</a>
              </li>
              <li class="menu-item">
                <a href="#">Báo cáo vấn đề</a>
              </li>
              <li class="menu-item">
                <a href="#">Đề xuất ý kiến</a>
              </li>
              <li class="menu-item">
                <a href="#">Liên hệ đến chúng tôi</a>
              </li>
            </ul>
          </div>

        </div>

      </div>
      <!-- / row -->

    </div>
  </footer>

  <div id="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-md-12">

          <div class="copyright">
            <div class="row">

              

              <div class="col-md-6">
                <div class="social-links align-right">
                  <ul>
                    <li>
                      <a href="#"><i class="icon icon-facebook"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="icon icon-twitter"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="icon icon-youtube-play"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="icon icon-behance-square"></i></a>
                    </li>
                  </ul>
                </div>
              </div>

            </div>
          </div>
          <!--grid-->

        </div>
        <!--footer-bottom-content-->
      </div>
    </div>
  </div>

  <script src="<?php echo BASE_URL; ?>/js/jquery-1.11.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" ...>
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
  </script>
  <script src="<?php echo BASE_URL; ?>/js/plugins.js"></script>
  <script src="<?php echo BASE_URL; ?>/js/script.js"></script>