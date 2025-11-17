<?php
session_start();
require_once('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
    $store_name = isset($_POST['store_name']) ? trim($_POST['store_name']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (!$review_id || !$rating || empty($content) || empty($store_name) || empty($address)) {
        header('Location: trangcanhan.php?error=invalid_input');
        exit();
    }

    // Verify that the review belongs to the user
    $check_query = "SELECT r.*, s.store_id FROM reviews r 
                   JOIN stores s ON r.store_id = s.store_id 
                   WHERE r.review_id = ? AND r.user_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $review_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $review = mysqli_fetch_assoc($result);

    if ($review) {
        // Start transaction
        mysqli_begin_transaction($conn);
        try {
            // Update store information
            $update_store = "UPDATE stores SET store_name = ?, address = ? WHERE store_id = ?";
            $stmt = mysqli_prepare($conn, $update_store);
            mysqli_stmt_bind_param($stmt, "ssi", $store_name, $address, $review['store_id']);
            mysqli_stmt_execute($stmt);

            // Update review
            $update_review = "UPDATE reviews SET rating = ?, content = ?, updated_at = NOW() WHERE review_id = ?";
            $stmt = mysqli_prepare($conn, $update_review);
            mysqli_stmt_bind_param($stmt, "isi", $rating, $content, $review_id);
            mysqli_stmt_execute($stmt);

            // Commit transaction
            mysqli_commit($conn);
            header('Location: trangcanhan.php?success=1');
        } catch (Exception $e) {
            mysqli_rollback($conn);
            header('Location: trangcanhan.php?error=update_failed');
        }
    } else {
        header('Location: trangcanhan.php?error=unauthorized');
    }
} else {
    header('Location: trangcanhan.php');
}
exit();