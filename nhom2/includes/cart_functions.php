<?php
require_once 'config.php';
//THỰC HIỆN TÍNH TOÁN CÁC THAO TÁC CÓ THỂ SẢY RA TRÊN GIỎ HÀNG
// Hàm để lấy giỏ hàng hiện tại
function getCart() {
    global $conn;
    $session_id = session_id();
    
    $stmt = $conn->prepare(
        "SELECT c.id, c.product_id, c.quantity, p.name, p.price, pi.image_url as image_url, (p.price * c.quantity) as subtotal 
         FROM cart c
         JOIN products p ON c.product_id = p.id
         LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.sort_order = 1
         WHERE c.session_id = ?"
    );
    
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $cart_items = [];
    
    while ($item = $result->fetch_assoc()) {
        $cart_items[] = $item;
    }
    
    return $cart_items;
}

// Nếu có lỗi trong hàm này, bạn có thể sử dụng phiên bản dưới đây
function addToCart($product_id, $quantity = 1) {
    global $conn;
    $session_id = session_id();
    
    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $session_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $cart_id = $row['id'];
        
        $stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $cart_id);
        return $stmt->execute();
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $session_id, $product_id, $quantity);
        return $stmt->execute();
    }
}

// Hàm để cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartItem($cart_id, $quantity) {
    global $conn;
    $session_id = session_id();
    
    if ($quantity <= 0) {
        // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
        return removeCartItem($cart_id);
    }
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ? AND session_id = ?");
    $stmt->bind_param("iis", $quantity, $cart_id, $session_id);
    return $stmt->execute();
}

// Hàm để xóa sản phẩm khỏi giỏ hàng
function removeCartItem($cart_id) {
    global $conn;
    $session_id = session_id();
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
    $stmt->bind_param("is", $cart_id, $session_id);
    return $stmt->execute();
}

// Hàm để làm trống giỏ hàng
function clearCart() {
    global $conn;
    $session_id = session_id();
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    return $stmt->execute();
}

// Hàm để tính tổng giỏ hàng
function getCartTotal() {
    $cart_items = getCart();
    $total = 0;
    
    foreach ($cart_items as $item) {
        $total += $item['subtotal'];
    }
    
    return $total;
}

// Hàm để đếm số lượng sản phẩm trong giỏ hàng
function getCartItemCount() {
    $cart_items = getCart();
    $count = 0;
    
    foreach ($cart_items as $item) {
        $count += $item['quantity'];
    }
    
    return $count;
}
?>