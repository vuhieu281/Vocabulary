<?php
session_start();
require_once('config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle personal information update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } else {
        $query = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $phone, $address, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Cập nhật thông tin thành công!';
            $_SESSION['username'] = $username;
        } else {
            $error = 'Có lỗi xảy ra khi cập nhật thông tin';
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get current password from database
    $query = "SELECT password FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!password_verify($current_password, $user['password'])) {
        $error = 'Mật khẩu hiện tại không đúng';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp';
    } elseif (strlen($new_password) < 8) {
        $error = 'Mật khẩu mới phải có ít nhất 8 ký tự';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Đổi mật khẩu thành công!';
        } else {
            $error = 'Có lỗi xảy ra khi đổi mật khẩu';
        }
    }
}

// Get user information
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông tin cá nhân - TechBook</title>
    <link rel="stylesheet" href="assets/css/thongtincanhan.css" />
</head>
<body>
    <!-- Header -->
    <?php 
    define('INCLUDED', true);
    include 'includes/header.php'; ?>

    <!-- Body -->
    <div class="container">
        <h1>Thông tin cá nhân</h1>

        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Personal Information Section -->
        <div class="settings-card">
            <h2>Thông tin cá nhân</h2>
            <form class="settings-form" method="POST" action="thongtincanhan.php">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="form-group">
                    <label for="username">Tên</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($user['phone']); ?>" />
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" id="address" name="address" 
                           value="<?php echo htmlspecialchars($user['address']); ?>" />
                </div>

                <button type="submit" class="btn-update">Cập nhật thông tin</button>
            </form>
        </div>

        <!-- Password Change Section -->
        <div class="settings-card">
            <h2>Đổi mật khẩu</h2>
            <form class="settings-form" method="POST" action="thongtincanhan.php">
                <input type="hidden" name="change_password" value="1">
                
                <div class="form-group">
                    <label for="current-password">Mật khẩu cũ</label>
                    <input type="password" id="current-password" name="current_password" required />
                </div>

                <div class="form-group">
                    <label for="new-password">Mật khẩu mới</label>
                    <input type="password" id="new-password" name="new_password" required />
                </div>

                <div class="form-group">
                    <label for="confirm-password">Xác nhận mật khẩu mới</label>
                    <input type="password" id="confirm-password" name="confirm_password" required />
                </div>

                <button type="submit" class="btn-update">Đổi mật khẩu</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>