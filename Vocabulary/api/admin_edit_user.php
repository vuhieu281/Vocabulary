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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if (empty($name) || empty($email)) {
        header("Location: ../index.php?route=admin_users&error=Dữ liệu không hợp lệ");
        exit;
    }

    $result = $admin->updateUser($id, $name, $email, $role);

    if ($result) {
        header("Location: ../index.php?route=admin_users&success=Cập nhật người dùng thành công");
    } else {
        header("Location: ../index.php?route=admin_edit_user&id=$id&error=Cập nhật thất bại");
    }
} else {
    header("Location: ../index.php?route=admin_users");
}
?>
