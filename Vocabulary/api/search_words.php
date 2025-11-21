<?php
session_start();
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';

header('Content-Type: application/json');

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'words' => []]);
    exit;
}

$user = new User();
$userData = $user->getById($_SESSION['user_id']);

if (!$userData || $userData['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'words' => []]);
    exit;
}

$search = $_GET['q'] ?? '';
$search = trim($search);

try {
    $admin = new Admin();
    $words = $admin->searchWords($search, 50);
    echo json_encode(['success' => true, 'words' => $words]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'words' => []]);
}
?>
