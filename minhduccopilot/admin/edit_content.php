<?php
session_start();
require_once('../../config/db.php');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../dangnhap.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $query = "UPDATE site_content SET content = ? WHERE type = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $content, $type);

    if (mysqli_stmt_execute($stmt)) {
        $success = 'Nội dung đã được cập nhật thành công!';
    } else {
        $error = 'Có lỗi xảy ra khi cập nhật nội dung';
    }
}

// Get current content
$query = "SELECT * FROM site_content";
$result = mysqli_query($conn, $query);
$contents = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý nội dung - TechBook Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Quản lý nội dung</h1>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php foreach ($contents as $content): ?>
            <form method="POST" class="content-form">
                <h2><?php echo ucfirst(str_replace('_', ' ', $content['type'])); ?></h2>
                <input type="hidden" name="type" value="<?php echo $content['type']; ?>">
                <textarea name="content" rows="10"><?php echo htmlspecialchars($content['content']); ?></textarea>
                <button type="submit">Cập nhật</button>
            </form>
        <?php endforeach; ?>
    </div>
</body>
</html>