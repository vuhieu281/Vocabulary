<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "techbuc";
session_start();

// Initialize variables for error messages
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate input
    if (empty($email)) {
        $errors['email'] = "Email không được để trống";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    }
    
    if (empty($username)) {
        $errors['username'] = "Tên người dùng không được để trống";
    }
    
    if (empty($password)) {
        $errors['password'] = "Mật khẩu không được để trống";
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $errors['email'] = "Email đã được sử dụng";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);
                
                $success = true;
                header("Location: dangnhap.php");
                exit();
            }
        } catch(PDOException $e) {
            $errors['db'] = "Lỗi đăng ký: " . $e->getMessage();
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
    <link rel="stylesheet" href="./assets/css/dangky.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <h1><a href="./index.php">TechBook</a></h1>
            <nav class="nav">
                <a href="./index.php">TRANG CHỦ</a>
                <a href="./baidang.php">BÀI ĐĂNG</a>
                <a href="#">GIỚI THIỆU</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a href="#" class="account">
                            TÀI KHOẢN 
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="./trangcanhan.php">
                                <i class="fas fa-user"></i>
                                Trang cá nhân
                            </a>
                            <a href="./thongtincanhan.php">
                                <i class="fas fa-cog"></i>
                                Cài đặt
                            </a>
                            <hr class="dropdown-divider">
                            <a href="logout.php" class="logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Đăng xuất
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="./dangnhap.php" class="account">ĐĂNG NHẬP</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Body -->
    <div class="container">
        <div class="signup-card">
            <h1>Đăng ký</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="signup-form" method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required />
                </div>

                <div class="form-group">
                    <input type="text" name="username" placeholder="Tên người dùng" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required />
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
                Đã có tài khoản? <a href="./dangnhap.php">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="content">
            <div class="section">
                <h3>Về TechBook</h3>
                <p>Trang chuyên review cửa hàng đồ công nghệ secondhand uy tín tại Việt Nam</p>
                <p><i class="fas fa-envelope"></i> TechBook@gmail.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</p>
            </div>
            <div class="section">
                <h3>Hỗ trợ khách hàng</h3>
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
            </div>
            <div class="section">
                <h3>Chính sách</h3>
                <a href="#">Chính sách quyền riêng tư</a>
                <a href="#">Điều khoản & điều kiện</a>
            </div>
        </div>
        <div class="policy">
            <p>&copy; 2025 TechBook. Tất cả quyền được bảo lưu.</p>
        </div>
    </footer>
</body>
</html>