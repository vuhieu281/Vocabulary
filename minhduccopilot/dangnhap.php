<?php
session_start();
require_once('config/db.php');

// If already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else {
        // Get user from database - Changed 'id' to 'user_id'
        $query = "SELECT user_id, email, username, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables - Changed 'id' to 'user_id'
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            // Redirect to home page
            header('Location: index.php');
            exit();
        } else {
            $error = 'Email hoặc mật khẩu không đúng';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập - TechBook</title>
    <link rel="stylesheet" href="assets/css/dangnhap.css" />
</head>
<body>
    <!-- Header -->
    <?php
    define('INCLUDED', true); 
    include 'includes/header.php'; ?>

    <!-- Body -->
    <div class="container">
        <div class="login-card">
            <h1>Đăng nhập</h1>

            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="dangnhap.php">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Mật khẩu" required />
                </div>

                <button type="submit" class="btn-login">Đăng nhập</button>
            </form>

            <div class="signup-link">
                Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>