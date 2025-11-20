<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';

// Check admin access
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header("Location: ../index.php?route=login");
    exit;
}

$user = new User();
$userData = $user->getById($_SESSION['user_id']);

if (!$userData || $userData['role'] !== 'admin') {
    http_response_code(403);
    header("Location: ../index.php?route=admin_dashboard&error=Không có quyền");
    exit;
}

$admin = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $image = null;

    if (empty($name)) {
        header("Location: ../index.php?route=admin_topics&error=Tên chủ đề không được để trống");
        exit;
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            header("Location: ../index.php?route=admin_add_topic&error=Định dạng ảnh không hỗ trợ");
            exit;
        }

        if ($_FILES['image']['size'] > 2 * 1024 * 1024) { // 2MB
            header("Location: ../index.php?route=admin_add_topic&error=Kích thước ảnh quá lớn");
            exit;
        }

        $uploads_dir = __DIR__ . '/../uploads/topics';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0755, true);
        }

        $new_filename = uniqid() . '.' . $ext;
        $upload_path = $uploads_dir . '/' . $new_filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image = 'uploads/topics/' . $new_filename;
        }
    }

    $result = $admin->createTopic($name, $description, $image);

    if ($result) {
        header("Location: ../index.php?route=admin_topics&success=Thêm chủ đề thành công");
    } else {
        header("Location: ../index.php?route=admin_add_topic&error=Thêm chủ đề thất bại");
    }
} else {
    header("Location: ../index.php?route=admin_topics");
}
?>
