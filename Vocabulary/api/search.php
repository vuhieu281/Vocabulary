<?php
// api/search.php - API endpoint để tìm kiếm từ vựng

header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';
require_once '../models/Word.php';

// Kết nối database
$database = new Database();
$db = $database->connect();

// Khởi tạo model Word (Word constructor accepts an optional PDO)
$word = new Word($db);

// Lấy tham số từ request
$keyword = isset($_GET['q']) ? $_GET['q'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$level = isset($_GET['level']) ? $_GET['level'] : '';

// Tính toán offset
$offset = ($page - 1) * $limit;

// Response mặc định
$response = [
    'success' => false,
    'message' => '',
    'data' => [],
    'total' => 0,
    'page' => $page,
    'limit' => $limit
];

try {
    // Nếu không có keyword, trả về tất cả từ vựng
    if (empty($keyword) && empty($level)) {
        $results = $word->getAll($limit, $offset);
        $response['success'] = true;
        $response['message'] = 'Lấy danh sách từ vựng thành công';
        $response['data'] = $results;
    }
    // Tìm kiếm theo level
    elseif (!empty($level)) {
        $results = $word->getByLevel($level, $limit, $offset);
        $total = count($results);
        $response['success'] = true;
        $response['message'] = 'Tìm kiếm theo level thành công';
        $response['data'] = $results;
        $response['total'] = $total;
    }
    // Tìm kiếm theo keyword
    else {
        error_log("Searching for keyword: " . $keyword);
        $results = $word->search($keyword, $limit, $offset);
        $total = $word->countSearch($keyword);
        error_log("Results count: " . count($results));
        
        if (empty($results)) {
            $response['success'] = false;
            $response['message'] = 'Không tìm thấy từ vựng nào';
            $response['data'] = [];
            $response['total'] = 0;
        } else {
            $response['success'] = true;
            $response['message'] = 'Tìm kiếm thành công';
            $response['data'] = $results;
            $response['total'] = $total;
        }
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Lỗi: ' . $e->getMessage();
    $response['data'] = [];
}

// Trả về JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
