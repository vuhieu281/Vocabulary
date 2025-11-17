<?php
require_once('auth.php');
require_once('../config/db.php');

// Thống kê cơ bản
$stats = [
    'users' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'],
    'reviews' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reviews"))['count'],
    'stores' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM stores"))['count'],
    'comments' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM comments"))['count']
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <div class="stats-container">
            <div class="stat-card">
                <h3>Người dùng</h3>
                <p><?php echo $stats['users']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Đánh giá</h3>
                <p><?php echo $stats['reviews']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Cửa hàng</h3>
                <p><?php echo $stats['stores']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Bình luận</h3>
                <p><?php echo $stats['comments']; ?></p>
            </div>
        </div>
        
        <div class="quick-actions">
            <h2>Quản lý nhanh</h2>
            <div class="action-buttons">
                <a href="users.php" class="btn">Quản lý người dùng</a>
                <a href="reviews.php" class="btn">Quản lý đánh giá</a>
                <a href="stores.php" class="btn">Quản lý cửa hàng</a>
                <a href="comments.php" class="btn">Quản lý bình luận</a>
            </div>
        </div>
    </div>
</body>
</html>