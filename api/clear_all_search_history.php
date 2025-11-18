<?php
// api/clear_all_search_history.php - Xóa toàn bộ lịch sử tìm kiếm của user

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once '../config/database.php';
require_once '../models/SearchHistory.php';

$response = [
    'success' => false,
    'message' => ''
];

try {
    // Kiểm tra user đã đăng nhập chưa
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Vui lòng đăng nhập';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $searchHistory = new SearchHistory();
    if ($searchHistory->deleteAll($_SESSION['user_id'])) {
        $response['success'] = true;
        $response['message'] = 'Đã xóa toàn bộ lịch sử tìm kiếm';
    } else {
        $response['message'] = 'Không thể xóa lịch sử tìm kiếm';
    }
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
