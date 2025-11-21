<?php
// api/get_search_history.php - Lấy lịch sử tìm kiếm của user

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once '../config/database.php';
require_once '../models/SearchHistory.php';

$response = [
    'success' => false,
    'message' => '',
    'data' => [],
    'total' => 0
];

try {
    // Kiểm tra user đã đăng nhập chưa
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Vui lòng đăng nhập';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $searchHistory = new SearchHistory();
    $data = $searchHistory->getByUserId($_SESSION['user_id'], (int)$limit, (int)$offset);
    $total = $searchHistory->countByUserId($_SESSION['user_id']);

    $response['success'] = true;
    $response['message'] = 'Lấy lịch sử tìm kiếm thành công';
    $response['data'] = $data;
    $response['total'] = $total;
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
