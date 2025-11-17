<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

/**
 * Kiểm tra xem người dùng đã đăng nhập chưa
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['MaKH']);
}

/**
 * Đăng nhập người dùng
 * @param string $email
 * @param string $matkhau
 * @return bool
 */
function login($email, $matkhau) {
    $conn = connectDatabase();
    if (!$conn) {
        error_log("Kết nối database thất bại");
        return false;
    }

    // Lấy thông tin khách hàng theo email
    $stmt = $conn->prepare("SELECT MaKH, HoTen, Email, MatKhau FROM KhachHang WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // So sánh mật khẩu dạng thuần (vì bạn đang lưu plain text)
        if ($matkhau === $row['MatKhau']) {
            $_SESSION['MaKH'] = $row['MaKH'];
            $_SESSION['HoTen'] = $row['HoTen'];
            $_SESSION['Email'] = $row['Email'];
            $stmt->close();
            $conn->close();
            return true;
        } else {
            error_log("Sai mật khẩu cho tài khoản $email");
        }
    } else {
        error_log("Không tìm thấy người dùng có email $email");
    }

    $stmt->close();
    $conn->close();
    return false;
}

/**
 * Đăng xuất người dùng
 */

function logout() {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/'); // Xóa cookie session
    header('Location: login.php');
    exit;
}

/**
 * Bảo vệ trang yêu cầu đăng nhập
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

// Nếu có tham số ?logout thì đăng xuất
if (isset($_GET['logout'])) {
    logout();
}
?>