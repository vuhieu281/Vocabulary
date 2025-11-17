<?php
session_start(); // Bắt đầu session

// Hủy toàn bộ session
$_SESSION = [];
session_unset();
session_destroy();

// Xóa cookie ghi nhớ đăng nhập (nếu có)
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}

// Chuyển hướng về trang chủ hoặc trang đăng nhập
header("Location: index.php?message=logout_success");
exit();
?>