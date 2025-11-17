<?php
//Cấu hình hành động của giỏ hàng nhé.
require_once 'config.php';
require_once 'cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if (empty($action)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin hành động']);
        exit;
    }
    

    switch ($action) {
        case 'add':
            // Thêm sản phẩm vào giỏ hàng 
            if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
                exit;
            }
            
            $product_id = (int)$_POST['product_id'];
            $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if ($quantity <= 0) {
                echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']);
                exit;
            }
            
            // Kiểm tra sản phẩm có tồn tại không
            $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
                exit;
            }
            
            // hành động thêm vào giỏ hàng
            if (addToCart($product_id, $quantity)) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                    'cart_count' => getCartItemCount(),
                    'cart_total' => number_format(getCartTotal(), 0, ',', '.')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm vào giỏ hàng']);
            }
            break;
            
        case 'update':
//Cập nhật lại số lượng sản phẩmphẩm
            if (!isset($_POST['cart_id']) || !is_numeric($_POST['cart_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID giỏ hàng không hợp lệ']);
                exit;
            }
            
            if (!isset($_POST['quantity']) || !is_numeric($_POST['quantity'])) {
                echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']);
                exit;
            }
            
            $cart_id = (int)$_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];
            
    // Nút cập nhật giỏ hàng
            if (updateCartItem($cart_id, $quantity)) {
                $cart_items = getCart();
                $cart_total = getCartTotal();
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đã cập nhật giỏ hàng',
                    'cart_items' => $cart_items,
                    'cart_count' => getCartItemCount(),
                    'cart_total' => number_format($cart_total, 0, ',', '.')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật giỏ hàng']);
            }
            break;
            
        case 'remove':
            // Xóa sản phẩm khỏi giỏ hàng
            if (!isset($_POST['cart_id']) || !is_numeric($_POST['cart_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID giỏ hàng không hợp lệ']);
                exit;
            }
            
            $cart_id = (int)$_POST['cart_id'];
            
            // Xóa khỏi giỏ hàng
            if (removeCartItem($cart_id)) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                    'cart_count' => getCartItemCount(),
                    'cart_total' => number_format(getCartTotal(), 0, ',', '.')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm khỏi giỏ hàng']);
            }
            break;
            
        case 'clear':
            // Xóa toàn bộ giỏ hàng
            if (clearCart()) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đã xóa toàn bộ giỏ hàng',
                    'cart_count' => 0,
                    'cart_total' => '0'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa giỏ hàng']);
            }
            break;
            
        case 'get':
            // Lấy thông tin giỏ hàng
            $cart_items = getCart();
            $cart_total = getCartTotal();
            
            echo json_encode([
                'success' => true,
                'cart_items' => $cart_items,
                'cart_count' => getCartItemCount(),
                'cart_total' => number_format($cart_total, 0, ',', '.')
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không được hỗ trợ']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
}
?>