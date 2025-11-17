<?php
/**
 * Trang Danh Sách Phim - Hệ Thống Đặt Vé Xem Phim
 */

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/function.php';

// Lấy tham số từ URL
$trangThai = isset($_GET['trang_thai']) ? urldecode($_GET['trang_thai']) : '';
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Lấy danh sách phim theo trạng thái và tìm kiếm
$phimList = getPhimList($trangThai, $query);

// Số phim trên mỗi trang
$perPage = 8;
$totalPhim = count($phimList);
$totalPages = ceil($totalPhim / $perPage);
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, min($totalPages, $_GET['page'])) : 1;
$startIndex = ($currentPage - 1) * $perPage;
$phimListPaged = array_slice($phimList, $startIndex, $perPage);

// Tiêu đề trang
$pageTitle = $trangThai ? "Phim $trangThai" : 'Tất Cả Phim';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Hệ Thống Đặt Vé Xem Phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/phim.css">

</head>
<body>
    <!-- Phần Header -->
    <header class="header-section">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    </header>


    <!-- Phần Danh Sách Phim -->
    <section class="phim-section">
        <div class="container">
            <div class="phim-list">
                <?php foreach ($phimListPaged as $phim): ?>
                <div class="phim-card animate">
                    <div class="phim-image">
                        <img src="<?php echo htmlspecialchars($phim['Poster']); ?>" alt="<?php echo htmlspecialchars($phim['TenPhim']); ?>">
                    </div>
                    <div class="phim-details">
                        <h3><a href="phim-details.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="text-decoration-none text-dark"><?php echo htmlspecialchars($phim['TenPhim']); ?></a></h3>
                        <div class="phim-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $phim['ThoiLuong']; ?> phút</span>
                            <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($phim['NgayKhoiChieu'])); ?></span>
                        </div>
                        <a href="phim-details.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="btn btn-custom mt-2">Xem chi tiết</a>
                        <?php if (isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="delete-phim.php?ma_phim=<?php echo $phim['MaPhim']; ?>" class="btn btn-danger btn-sm mt-2">Xóa</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Phân trang -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?trang_thai=<?php echo urlencode($trangThai); ?>&query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Hệ Thống Đặt Vé Xem Phim.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hiệu ứng animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.phim-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate');
                }, index * 150);
            });
        });
    </script>
</body>
</html>
 