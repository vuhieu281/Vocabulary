<?php
session_start();
require_once('config/db.php');

// If already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp';
    } elseif (strlen($password) < 8) {
        $error = 'Mật khẩu phải có ít nhất 8 ký tự';
    } else {
        // Check if email already exists - Changed 'id' to 'user_id'
        $check_query = "SELECT user_id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email đã được sử dụng';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
                // Redirect after 2 seconds
                header("refresh:2;url=dangnhap.php");
            } else {
                $error = 'Có lỗi xảy ra, vui lòng thử lại sau';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký - TechBook</title>
    <link rel="stylesheet" href="assets/css/dangky.css" />
</head>
<body>
    <!-- Header -->
    <?php
    define('INCLUDED', true); 
    include 'includes/header.php'; ?>

    <!-- Body -->
    <div class="container">
        <div class="signup-card">
            <h1>Đăng ký</h1>

            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <form class="signup-form" method="POST" action="dangky.php">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <input type="text" name="username" placeholder="Tên người dùng" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Mật khẩu" required />
                </div>

                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required />
                </div>

                <button type="submit" class="btn-signup">Đăng ký</button>
            </form>

            <div class="login-link">
                Đã có tài khoản? <a href="dangnhap.php">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>