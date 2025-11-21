<?php
session_start();
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';

// Check admin access
$user = new User();
$userData = $user->getById($_SESSION['user_id'] ?? null);

if (!$userData || $userData['role'] !== 'admin') {
    http_response_code(403);
    die('Truy cập bị từ chối.');
}

$admin = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $result = $admin->deleteWord($id);

    if ($result) {
        header("Location: ../index.php?route=admin_words&success=Xóa từ vựng thành công");
    } else {
        header("Location: ../index.php?route=admin_words&error=Xóa thất bại");
    }
} else {
    header("Location: ../index.php?route=admin_words");
}
?>
