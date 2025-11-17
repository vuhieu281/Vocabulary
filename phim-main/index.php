<?php
/**
 * Trang Chủ - Hệ Thống Đặt Vé Xem Phim
 */
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/function.php';

if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">Đặt vé thành công!</div>';
}
// Lấy danh sách phim đang chiếu và sắp chiếu
$dangChieu = getPhimList('Đang chiếu');
$sapChieu = getPhimList('Sắp chiếu');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Đặt Vé Xem Phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- Phần Hero Carousel -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.pexels.com/photos/33129/popcorn-movie-party-entertainment.jpg');">
                <div class="carousel-caption">
                    <h1><?php echo htmlspecialchars($heroTitle ?? 'Chào Mừng Đến Với Hệ Thống Đặt Vé'); ?></h1>
                    <p><?php echo htmlspecialchars($heroSubtitle ?? 'Đặt vé dễ dàng, thưởng thức phim đỉnh cao!'); ?></p>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?q=80&w=2070&auto=format&fit=crop');">
                <div class="carousel-caption">
                    <h1>Trải Nghiệm Điện Ảnh Tuyệt Vời</h1>
                    <p>Đặt vé ngay để không bỏ lỡ những khoảnh khắc đặc biệt!</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- Phim Đang Chiếu -->
<section class="movies-section py-5">
    <div class="container">
        <div class="section-heading text-center mb-5">
            <span class="section-subtitle">Phim Đang Chiếu</span>
            <h2 class="section-title">Khám Phá Ngay</h2>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($dangChieu, 0, 4) as $phim): ?>
            <div class="col-md-3">
                <div class="phim-card animate">
                    <div class="phim-image">
                        <img src="<?php echo htmlspecialchars($phim['Poster']); ?>" alt="<?php echo htmlspecialchars($phim['TenPhim']); ?>">
                        <!-- Thêm tên phim trên poster -->
                        <div class="phim-title-overlay">
                            <h4 class="phim-title-text"><?php echo htmlspecialchars($phim['TenPhim']); ?></h4>
                        </div>
                    </div>
                    <div class="phim-details">
                        <h3><a href="phim-details.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($phim['TenPhim']); ?></a></h3>
                        <div class="phim-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $phim['ThoiLuong']; ?> phút</span>
                            <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($phim['NgayKhoiChieu'])); ?></span>
                            <span><i class="fas fa-ticket"></i> <?php echo number_format($phim['GiaCoBan'], 0, ',', '.'); ?> VNĐ</span>
                        </div>
                        <a href="phim-details.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="btn btn-custom mt-2">Xem chi tiết</a>
                        <?php if (isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="delete-phim.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="btn btn-danger btn-sm mt-2">Xóa</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="phim.php?trang_thai=Đang chiếu" class="btn btn-primary">Xem tất cả</a>
        </div>
    </div>
</section>

<!-- Phim Sắp Chiếu -->
<section class="movies-section py-5">
    <div class="container">
        <div class="section-heading text-center mb-5">
            <span class="section-subtitle">Phim Sắp Chiếu</span>
            <h2 class="section-title">Sắp Ra Mắt</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($sapChieu as $phim): ?>
            <div class="col-md-3">
                <div class="phim-card animate">
                    <div class="phim-image">
                        <img src="<?php echo htmlspecialchars($phim['Poster']); ?>" alt="<?php echo htmlspecialchars($phim['TenPhim']); ?>">
                        <!-- Thêm tên phim trên poster -->
                        <div class="phim-title-overlay">
                            <h4 class="phim-title-text"><?php echo htmlspecialchars($phim['TenPhim']); ?></h4>
                        </div>
                    </div>
                    <div class="phim-details">
                        <h3><a href="phim-details.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($phim['TenPhim']); ?></a></h3>
                        <div class="phim-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $phim['ThoiLuong']; ?> phút</span>
                            <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($phim['NgayKhoiChieu'])); ?></span>
                            <span><i class="fas fa-ticket"></i> <?php echo number_format($phim['GiaCoBan'], 0, ',', '.'); ?> VNĐ</span>
                        </div>
                        <a href="phim-details.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="btn btn-custom mt-2">Xem chi tiết</a>
                        <?php if (isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="delete-phim.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="btn btn-danger btn-sm mt-2">Xóa</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="phim.php?trang_thai=Sắp chiếu" class="btn btn-primary">Xem tất cả</a>
        </div>
    </div>
</section>

    <!-- Phần Giới Thiệu -->
    <section class="about-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="text-center mb-4">Về Chúng Tôi</h2>
                    <p class="lead">Chúng tôi mang đến trải nghiệm đặt vé xem phim trực tuyến tiện lợi, với hàng loạt bộ phim hấp dẫn và dịch vụ chất lượng cao.</p>
                    <div class="about-stats text-center">
                        <div class="stat-item">
                            <div class="stat-value">1000+</div>
                            <div class="stat-label">Khách Hàng</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">50+</div>
                            <div class="stat-label">Bộ Phim</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">10+</div>
                            <div class="stat-label">Rạp Chiếu</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-images">
                        <img src="https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?q=80&w=2070&auto=format&fit=crop" alt="Rạp chiếu phim" class="img-fluid rounded shadow w-100 mb-2">

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Phần CTA -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-3">Đặt Vé Ngay Hôm Nay</h2>
            <p class="lead mb-4">Khám phá và đặt vé để tận hưởng những bộ phim tuyệt vời nhất!</p>
            <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="btn btn-primary btn-lg">Đăng ký ngay</a>
            <?php else: ?>
                <a href="phim.php" class="btn btn-primary btn-lg">Đặt vé ngay</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; <?php echo date('Y'); ?> Hệ Thống Đặt Vé Xem Phim. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Khởi tạo carousel
        const swiper = new Swiper('.hero-carousel', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.carousel-control-next',
                prevEl: '.carousel-control-prev',
            },
        });

        // Hiệu ứng animation cho phim card
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.phim-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate');
                }, index * 200);
            });
        });
    </script>
</body>
</html>
