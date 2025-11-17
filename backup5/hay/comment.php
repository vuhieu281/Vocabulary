<?php
session_start();
require_once('config/db.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;
    $content = isset($_POST['comment_content']) ? trim($_POST['comment_content']) : '';
    $user_id = $_SESSION['user_id'];

    if (empty($content)) {
        $_SESSION['error'] = 'Nội dung bình luận không được để trống';
        header('Location: baidang.php');
        exit;
    }

    // Thêm bình luận vào database
    $query = "INSERT INTO comments (review_id, user_id, content, created_at) 
              VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $review_id, $user_id, $content);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = 'Thêm bình luận thành công';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm bình luận';
    }

    // Chuyển hướng về trang bài đánh giá
    header('Location: baidang.php#review-' . $review_id);
    exit;
}

// Nếu không phải POST request, chuyển hướng về trang chủ
header('Location: index.php');
exit;