<?php
// api/check_saved_word.php - Kiểm tra từ đã được lưu chưa

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once '../config/database.php';

$response = [
    'success' => false,
    'is_saved' => false
];

try {
    if (isset($_SESSION['user_id'])) {
        $word_id = isset($_GET['word_id']) ? (int)$_GET['word_id'] : 0;

        if ($word_id > 0) {
            $db = (new Database())->connect();
            $stmt = $db->prepare("
                SELECT id FROM saved_words 
                WHERE user_id = ? AND local_word_id = ?
            ");
            $stmt->execute([$_SESSION['user_id'], $word_id]);
            $result = $stmt->fetch();
            
            $response['success'] = true;
            $response['is_saved'] = $result ? true : false;
        }
    }
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
