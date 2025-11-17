<?php
require_once 'config/database.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);

    // Validate input
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username exists
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = "Username or email already exists.";
            } else {
                // Insert new user
                $sql = "INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $full_name);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $success = "Registration successful! You can now login.";
                    } else {
                        $error = "Something went wrong. Please try again later.";
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
}

require_once 'includes/header.php';
?>

<div class="auth-container">
    <h2>Đăng ký</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form action="register.php" method="POST" class="auth-form">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="full_name">Họ và tên</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Đăng ký</button>
    </form>
    
    <p class="auth-links">
        Đã có tài khoản? <a href="login.php">Đăng nhập</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?> 