<?php
require_once 'config/db.php';
// Prevent direct access to this file 
defined('ALLOW_ACCESS') || define('ALLOW_ACCESS', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/dangky.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <title>TechBook</title>
</head>
<body>
<header class="header">
    <div class="header-container">
        <h1><a href="./index.php">TechBook</a></h1>
        <nav class="nav">
            <a href="./index.php">TRANG CHỦ</a>
            <a href="./baidang.php">BÀI ĐĂNG</a>
            <a href="./gioithieu.php">GIỚI THIỆU</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="./trangcanhan.php" class="icon-only" title="Trang cá nhân">
                    <i class="fas fa-user"></i>
                </a>
                <a href="./thongtincanhan.php" class="icon-only" title="Thông tin cá nhân">
                    <i class="fas fa-cog"></i>
                </a>
                <a href="logout.php" class="icon-only logout-btn" title="Đăng xuất">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <?php else: ?>
                <a href="./dangnhap.php" class="login-btn">ĐĂNG NHẬP</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
</body>
</html>