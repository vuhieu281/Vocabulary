<?php
// api/delete_search_history.php - Xóa lịch sử tìm kiếm

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

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id <= 0) {
        $response['message'] = 'ID lịch sử không hợp lệ';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $searchHistory = new SearchHistory();
    if ($searchHistory->delete($id)) {
        $response['success'] = true;
        $response['message'] = 'Xóa lịch sử tìm kiếm thành công';
    } else {
        $response['message'] = 'Không thể xóa lịch sử tìm kiếm';
    }
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
