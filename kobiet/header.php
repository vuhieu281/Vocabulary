<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techbook - Đánh giá đồ điện tử cũ</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/">Techbook</a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
            <div class="nav-links">
                <a href="/post-review.php">Đăng bài</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/profile.php">Tài khoản</a>
                    <a href="/logout.php">Đăng xuất</a>
                <?php else: ?>
                    <a href="/login.php">Đăng nhập</a>
                    <a href="/register.php">Đăng ký</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main>