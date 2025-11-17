<?php
session_start();
$host = 'localhost';
$username = 'root';       // Thay đổi tùy theo cấu hình máy chủ
$password = '';           // Thay đổi tùy theo cấu hình máy chủ
$database = 'thaoluan3';  // Tên cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>

<?php
session_start();
$host = 'localhost';
$username = 'root';       // Thay đổi tùy theo cấu hình máy chủ
$password = '';           // Thay đổi tùy theo cấu hình máy chủ
$database = 'Napthe24h';  // Tên cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>