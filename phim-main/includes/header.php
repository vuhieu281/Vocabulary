<?php
/**
 * ==========================================
 * HEADER TEMPLATE - HỆ THỐNG ĐẶT VÉ PHIM ONLINE
 * ==========================================
 */

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/function.php';

// Xác định người dùng hiện tại (nếu có)
$currentUser = isLoggedIn() ? ['name' => $_SESSION['username']] : [];

// Xác định trang hiện tại
$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = isset($pageTitle) ? $pageTitle : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo generatePageTitle($pageTitle ?? 'Trang chủ'); ?> - Rạp Chiếu Phim Online</title>
    
    <!-- CSS & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- CSS riêng cho từng trang -->
    <?php
    $cssFiles = [
        'index.php' => 'home.css',
        'phim.php' => 'phim.css',
    
        'booking.php' => 'booking.css',
        'contact.php' => 'contact.css',
        'login.php' => 'auth.css',
        'register.php' => 'auth.css',
        'account.php' => 'account.css'
    ];

    if (isset($cssFiles[$currentPage])) {
        echo '<link rel="stylesheet" href="assets/css/' . $cssFiles[$currentPage] . '">';
    }
    ?>
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="main-header shadow-sm">
    <div class="container d-flex align-items-center justify-content-between py-3">
        
        <!-- LOGO -->
        <div class="logo d-flex align-items-center">
            <a href="index.php" class="text-decoration-none text-dark d-flex align-items-center">
                <img src="assets/images/logo.png" alt="Rạp Phim Online" height="50" class="me-2">
                <h3 class="m-0 fw-bold text-danger">CineBook</h3>
            </a>
        </div>

        <!-- NAVIGATION -->
        <nav class="nav">
            <a href="index.php" class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">Trang chủ</a>
            <a href="phim.php" class="nav-link <?php echo $currentPage == 'phim.php' ? 'active' : ''; ?>">Phim</a>

            <a href="phim-details.php" class="nav-link <?php echo $currentPage == 'phim-details.php' ? 'active' : ''; ?>">Đặt vé</a>
            <a href="contact.php" class="nav-link <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>">Liên hệ</a>
        </nav>

        <!-- USER ACTIONS -->
        <div class="header-actions">
            <?php if (isLoggedIn()): ?>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($currentUser['name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="account.php" class="dropdown-item"><i class="fa fa-user"></i> Tài khoản</a></li>
                        <li><a href="booking-history.php" class="dropdown-item"><i class="fa fa-ticket"></i> Lịch sử đặt vé</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="index.php?logout=1" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-sm btn-outline-primary me-2"><i class="fa fa-sign-in-alt"></i> Đăng nhập</a>
                <a href="register.php" class="btn btn-sm btn-primary"><i class="fa fa-user-plus"></i> Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</header>