<?php
// api/save_search_history.php - Lưu lịch sử tìm kiếm của user

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

    // Lấy word_id từ request
    $word_id = isset($_POST['word_id']) ? (int)$_POST['word_id'] : 0;

    if ($word_id <= 0) {
        $response['message'] = 'ID từ vựng không hợp lệ';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Lưu lịch sử tìm kiếm
    $searchHistory = new SearchHistory();
    if ($searchHistory->save($_SESSION['user_id'], $word_id)) {
        $response['success'] = true;
        $response['message'] = 'Lưu lịch sử tìm kiếm thành công';
    } else {
        $response['message'] = 'Không thể lưu lịch sử tìm kiếm';
    }
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
