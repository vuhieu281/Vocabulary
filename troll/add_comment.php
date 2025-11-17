<?php
<?php
session_start();
require_once './model/pdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $review_id = $_POST['review_id'];
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);
    $rating = $_POST['rating'];

    try {
        $sql = "INSERT INTO comments (review_id, user_id, content, rating) 
                VALUES (?, ?, ?, ?)";
        pdo_execute($sql, $review_id, $user_id, $content, $rating);
        header("Location: baidang.php#review-" . $review_id);
        exit();
    } catch(PDOException $e) {
        header("Location: baidang.php?error=comment");
        exit();
    }
} else {
    header("Location: dangnhap.php");
    exit();
}