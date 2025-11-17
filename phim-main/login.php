<?php
/**
 * Trang đăng nhập - Hệ thống đặt vé xem phim
 */

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/function.php';

// Nếu người dùng đã đăng nhập → quay lại trang chính
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$email = '';

// Xử lý khi người dùng nhấn nút Đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
    } else {
        if (login($email, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email hoặc mật khẩu không chính xác.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Đặt Vé Xem Phim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>🎬 Đăng Nhập Tài Khoản</h2>

        <?php if (!empty($error)): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Nhập email của bạn" required>
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" class="btn">Đăng nhập</button>

            <div class="auth-links">
                <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
            </div>
        </form>
    </div>

    <script>
    // Hiện/ẩn mật khẩu
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", () => {
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;
        togglePassword.classList.toggle("fa-eye-slash");
    });
    </script>
</body>
</html>