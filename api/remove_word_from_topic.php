<?php
session_start();
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

$user = new User();
$userData = $user->getById($_SESSION['user_id']);

if (!$userData || $userData['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

$wordId = $_POST['word_id'] ?? null;
$topicId = $_POST['topic_id'] ?? null;

if (!$wordId || !$topicId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tham số không hợp lệ']);
    exit;
}

try {
    $admin = new Admin();
    $result = $admin->removeWordFromTopic($wordId, $topicId);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Xóa thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Xóa thất bại']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
