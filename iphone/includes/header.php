<?php
require_once 'config/db.php';
// Prevent direct access to this file 
defined('ALLOW_ACCESS') || define('ALLOW_ACCESS', true);
?>
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