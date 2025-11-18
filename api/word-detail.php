<?php
// api/word-detail.php - API endpoint để lấy chi tiết từ vựng

header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';
require_once '../models/Word.php';

// Kết nối database
$database = new Database();
$db = $database->getConnection();

// Khởi tạo model Word
$word = new Word($db);

// Lấy tham số từ request
$word_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$word_name = isset($_GET['word']) ? $_GET['word'] : '';

// Response mặc định
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    if ($word_id > 0) {
        // Tìm kiếm theo ID
        $result = $word->getById($word_id);
    } elseif (!empty($word_name)) {
        // Tìm kiếm từ chính xác
        $result = $word->searchExact($word_name);
    } else {
        throw new Exception('Vui lòng cung cấp ID hoặc tên từ vựng');
    }

    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Lấy chi tiết từ vựng thành công';
        $response['data'] = $result;
    } else {
        $response['success'] = false;
        $response['message'] = 'Không tìm thấy từ vựng';
        $response['data'] = null;
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Lỗi: ' . $e->getMessage();
    $response['data'] = null;
}

// Trả về JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
