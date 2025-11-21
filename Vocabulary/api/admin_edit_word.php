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

    $result = $admin->updateWord($id, $word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url);

    if ($result) {
        header("Location: ../index.php?route=admin_words&success=Cập nhật từ vựng thành công");
    } else {
        header("Location: ../index.php?route=admin_edit_word&id=$id&error=Cập nhật thất bại");
    }
} else {
    header("Location: ../index.php?route=admin_words");
}
?>
