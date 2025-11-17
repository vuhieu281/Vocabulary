<?php
session_start();
require_once 'config.php';
require_once 'cart_functions.php';

// Kiểm tra xem có tham số product_id được truyền không
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
    $quantity = isset($_GET['quantity']) && is_numeric($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
    
    // Thêm sản phẩm vào giỏ hàng
    if (addToCart($product_id, $quantity)) {
        // Chuyển hướng về trang giỏ hàng sau khi thêm thành công
        header('Location: giohang.php');
        exit;
    } else {
        echo "Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.";
    }
} else {
    // Nếu không có product_id hoặc không hợp lệ, chuyển hướng về trang sản phẩm
    header('Location: danhmucsp.php');
    exit;
}
?>