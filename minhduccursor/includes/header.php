<?php
session_start();

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techbook - Used Electronics Service Reviews</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="/" class="logo">Techbook</a>
            <div class="nav-links">
                <a href="/">Trang chủ</a>
                <a href="/services.php">Bài đăng</a>
                <a href="/about.php">Giới thiệu</a>
                <?php if ($logged_in): ?>
                    <a href="/profile.php">Tài khoản</a>
                    <a href="/logout.php">Đăng xuất</a>
                <?php else: ?>
                    <a href="/login.php">Đăng nhập</a>
                    <a href="/register.php">Đăng ký</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="container"> 