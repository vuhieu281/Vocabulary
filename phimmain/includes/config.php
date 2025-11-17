<?php
/**
 * Cấu hình Cơ sở dữ liệu
 * 
 * Tệp này chứa các thiết lập kết nối cơ sở dữ liệu và các cấu hình khác
 * cho website Hệ Thống Đặt Vé Xem Phim.
 */

// Bắt đầu session
session_start();

// Định nghĩa URL gốc của website (không có dấu gạch chéo ở cuối)
define('BASE_URL', 'http://localhost/1'); // Cập nhật theo thư mục dự án

// Định nghĩa ngôn ngữ mặc định (chỉ sử dụng tiếng Việt)
define('DEFAULT_LANGUAGE', 'vi');

// Định nghĩa các tham số kết nối cơ sở dữ liệu
define('DB_HOST', 'localhost');
define('DB_NAME', 'datvephim1');
define('DB_USER', 'root');         // Thay đổi thành tên người dùng MySQL của bạn
define('DB_PASS', '');             // Thay đổi thành mật khẩu MySQL của bạn

/**
 * Thiết lập kết nối cơ sở dữ liệu
 * 
 * @return mysqli|false Trả về kết nối cơ sở dữ liệu hoặc false nếu thất bại
 */
function connectDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        error_log("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
        return false;
    }
    
    // Thiết lập bộ mã hóa để xử lý ký tự tiếng Việt
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

// Khởi tạo kết nối mặc định
$conn = connectDatabase();
if ($conn === false) {
    die("Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra cấu hình.");
}

/**
 * Làm sạch đầu vào người dùng để ngăn chặn tấn công XSS
 * 
 * @param string $input Chuỗi đầu vào cần làm sạch
 * @return string Chuỗi đã được làm sạch
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Thiết lập múi giờ mặc định
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Múi giờ Việt Nam
?>