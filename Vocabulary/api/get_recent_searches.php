<?php
// api/get_recent_searches.php - Lấy tìm kiếm gần đây của user

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once '../config/database.php';
require_once '../models/SearchHistory.php';

$response = [
    'success' => false,
    'data' => [],
    'is_logged_in' => false
];

try {
    // Kiểm tra user đã đăng nhập chưa
    if (isset($_SESSION['user_id'])) {
        $searchHistory = new SearchHistory();
        $data = $searchHistory->getByUserId($_SESSION['user_id'], 7, 0); // Lấy 7 tìm kiếm gần đây

        $response['is_logged_in'] = true;
        $response['success'] = true;
        $response['data'] = $data;
    } else {
        // Nếu chưa đăng nhập, trả về danh sách từ phổ biến (mặc định)
        $response['is_logged_in'] = false;
        $response['success'] = true;
        $response['data'] = [
            ['word' => 'love', 'part_of_speech' => 'verb'],
            ['word' => 'success', 'part_of_speech' => 'noun'],
            ['word' => 'challenge', 'part_of_speech' => 'noun'],
            ['word' => 'opportunity', 'part_of_speech' => 'noun'],
            ['word' => 'growth', 'part_of_speech' => 'noun'],
            ['word' => 'friendship', 'part_of_speech' => 'noun'],
            ['word' => 'motivation', 'part_of_speech' => 'noun']
        ];
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
