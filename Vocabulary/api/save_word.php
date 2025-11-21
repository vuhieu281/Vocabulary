<?php
// api/save_word.php - Lưu/bỏ lưu từ vựng

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once '../config/database.php';

$response = [
    'success' => false,
    'message' => '',
    'is_saved' => false
];

try {
    // Kiểm tra user đã đăng nhập chưa
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Vui lòng đăng nhập';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $word_id = isset($_POST['word_id']) ? (int)$_POST['word_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : ''; // 'save' hoặc 'remove'

    if ($word_id <= 0) {
        $response['message'] = 'ID từ vựng không hợp lệ';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $db = (new Database())->connect();

    if ($action === 'save') {
        // Kiểm tra từ đã được lưu chưa
        $stmt = $db->prepare("
            SELECT id FROM saved_words 
            WHERE user_id = ? AND local_word_id = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $word_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            $response['message'] = 'Từ này đã được lưu';
            $response['is_saved'] = true;
        } else {
            // Lưu từ
            $stmt = $db->prepare("
                INSERT INTO saved_words (user_id, local_word_id, saved_at) 
                VALUES (?, ?, NOW())
            ");
            if ($stmt->execute([$_SESSION['user_id'], $word_id])) {
                $response['success'] = true;
                $response['message'] = 'Lưu từ thành công';
                $response['is_saved'] = true;
            } else {
                $response['message'] = 'Không thể lưu từ';
            }
        }
    } elseif ($action === 'remove') {
        // Xóa từ khỏi danh sách lưu
        $stmt = $db->prepare("
            DELETE FROM saved_words 
            WHERE user_id = ? AND local_word_id = ?
        ");
        if ($stmt->execute([$_SESSION['user_id'], $word_id])) {
            $response['success'] = true;
            $response['message'] = 'Đã bỏ lưu từ';
            $response['is_saved'] = false;
        } else {
            $response['message'] = 'Không thể bỏ lưu từ';
        }
    } else {
        $response['message'] = 'Hành động không hợp lệ';
    }
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
