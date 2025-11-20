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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])) {
    $word = trim($_POST['word']);
    $part_of_speech = trim($_POST['part_of_speech'] ?? '');
    $ipa = trim($_POST['ipa'] ?? '');
    $audio_link = trim($_POST['audio_link'] ?? '');
    $senses = trim($_POST['senses'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $oxford_url = trim($_POST['oxford_url'] ?? '');

    if (empty($word)) {
        header("Location: ../index.php?route=admin_words&error=Từ vựng không được để trống");
        exit;
    }

    $result = $admin->createWord($word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url);

    if ($result) {
        header("Location: ../index.php?route=admin_words&success=Thêm từ vựng thành công");
    } else {
        header("Location: ../index.php?route=admin_add_word&error=Thêm từ vựng thất bại");
    }
} else {
    header("Location: ../index.php?route=admin_words");
}
?>
