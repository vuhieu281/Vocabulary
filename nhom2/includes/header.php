<?php
require_once 'config.php';
require_once 'cart_functions.php';
$cart_count = getCartItemCount();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Too Beauty</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/chinhsach.css" />
    <link rel="stylesheet" href = "assets/css/danhmucsp.css"/>
    <link rel="stylesheet" href = "assets/css/thanhtoan.css"/>
    <link rel="stylesheet" href = "assets/css/trangchu.css"/>     
    <link rel="stylesheet" href = "assets/css/thongbao.css"/>
    <link rel="stylesheet" href = "assets/css/xacnhanthanhtoan.css"/>
    <!-- <link rel="stylesheet" href = "assets/css/giohang.css"/> -->
</head>
<body>
    <!-- Header -->
    <header>
    <div class="logo"><a href="index.php"><span>Too</span><span>Beauty</span></a></div>
    <nav>
        <ul>
            <li><a href="index.php">Trang chủ</a></li>
            <li><a href="danhmucsp.php">Sản phẩm</a></li>
            <li><a href="chinhsach.php">Giới thiệu</a></li>
            <li><a href="#footer">Liên hệ</a></li>
        </ul>
    </nav>
    <div class="user-actions">
        <!-- <a href="#"><i class="fas fa-search"></i> Tìm kiếm</a>
        <a href="#"><i class="fas fa-user"></i> Tài khoản</a> -->
        <a href="giohang.php" class="cart-icon">
            <div class="icon-container">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count" id="cart-count"><?php echo $cart_count; ?></span>
            </div>
            
        </a>
    </div>
</header>
