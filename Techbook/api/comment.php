<?php
session_start();
require_once('../config/db.php');

header('Content-Type: application/json');

// Debug mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Vui lòng đăng nhập']);
    exit();
}

// Log request data
error_log('Received POST data: ' . file_get_contents('php://input'));

// Nhận và decode JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate dữ liệu
if (!isset($data['review_id']) || !isset($data['rating']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Thiếu thông tin',
        'received' => $data
    ]);
    exit();
}

// Chuẩn bị dữ liệu
$review_id = (int)$data['review_id'];
$user_id = (int)$_SESSION['user_id'];
$rating = (int)$data['rating'];
$content = mysqli_real_escape_string($conn, $data['content']);

// Log prepared data
error_log("Preparing to insert: review_id=$review_id, user_id=$user_id, rating=$rating, content=$content");

// Insert comment
$query = "INSERT INTO comments (review_id, user_id, content, rating, created_at) 
          VALUES (?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    error_log("Prepare failed: " . mysqli_error($conn));
    http_response_code(500);
    echo json_encode(['error' => 'Database prepare failed']);
    exit();
}

mysqli_stmt_bind_param($stmt, "iisi", $review_id, $user_id, $content, $rating);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'success' => true,
        'message' => 'Bình luận đã được lưu thành công'
    ]);
} else {
    error_log("Execute failed: " . mysqli_stmt_error($stmt));
    http_response_code(500);
    echo json_encode([
        'error' => 'Lỗi khi lưu bình luận',
        'details' => mysqli_stmt_error($stmt)
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);