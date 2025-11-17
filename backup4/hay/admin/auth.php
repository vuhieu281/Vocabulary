<?php
session_start();
require_once('../config/db.php');

// Kiểm tra session và role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 1) {
    header('Location: ../dangnhap.php');
    exit();
}

// Verify admin status from database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ? AND role = 1 LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!mysqli_fetch_assoc($result)) {
    session_destroy();
    header('Location: ../dangnhap.php');
    exit();
}