<?php
// api/ajax_autocomplete.php - API cho chức năng gợi ý tìm kiếm (Autocomplete)

header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';
require_once '../models/Word.php';

// Kết nối database
$database = new Database();
$db = $database->getConnection();

// Khởi tạo model Word
$word = new Word($db);

// Lấy tham số từ request
$term = isset($_GET['term']) ? trim($_GET['term']) : '';

// Response mặc định
$response = [];

try {
    // Kiểm tra term có ít nhất 2 ký tự
    if (strlen($term) >= 2) {
        // Gọi method autocomplete từ model Word
        $results = $word->autocomplete($term);
        
        // Chuyển đổi kết quả thành mảng với id và word
        foreach ($results as $row) {
            $response[] = [
                'id' => $row['id'],
                'word' => $row['word']
            ];
        }
    }
} catch (Exception $e) {
    // Nếu có lỗi, trả về mảng rỗng
    $response = [];
}

// Trả về JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
