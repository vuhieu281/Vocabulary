<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "techbuc";
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu";
    } else {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] == 1) {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Email hoặc mật khẩu không chính xác";
            }
        } catch(PDOException $e) {
            $error = "Lỗi hệ thống, vui lòng thử lại sau";
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
    <link rel="stylesheet" href="./assets/css/dangnhap.css" />
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
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
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
        <div class="login-card">
            <h1>Đăng nhập</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required />
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Mật khẩu" required />
                </div>

                <button type="submit" class="btn-login">Đăng nhập</button>
            </form>

            <div class="signup-link">
                Chưa có tài khoản? <a href="./dangky.php">Đăng ký ngay</a>
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