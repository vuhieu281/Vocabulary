<?php
require_once('auth.php');
require_once('../config/db.php');

$error = '';
$success = '';

// Xử lý xóa cửa hàng
if (isset($_POST['delete'])) {
    $store_id = (int)$_POST['store_id'];
    
    try {
        mysqli_begin_transaction($conn);
        
        // Lấy danh sách review_id của store
        $reviews_query = "SELECT review_id FROM reviews WHERE store_id = ?";
        $stmt = mysqli_prepare($conn, $reviews_query);
        mysqli_stmt_bind_param($stmt, "i", $store_id);
        mysqli_stmt_execute($stmt);
        $reviews_result = mysqli_stmt_get_result($stmt);
        $review_ids = [];
        
        while ($row = mysqli_fetch_assoc($reviews_result)) {
            $review_ids[] = $row['review_id'];
        }
        
        // Free the result set
        mysqli_free_result($reviews_result);
        
        // Nếu có reviews, xóa ảnh và comments
        if (!empty($review_ids)) {
            $review_ids_str = implode(',', $review_ids);
            
            // Xóa ảnh
            $query = "DELETE FROM review_images WHERE review_id IN ($review_ids_str)";
            mysqli_query($conn, $query);
            
            // Xóa comments
            $query = "DELETE FROM comments WHERE review_id IN ($review_ids_str)";
            mysqli_query($conn, $query);
            
            // Xóa reviews
            $query = "DELETE FROM reviews WHERE store_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $store_id);
            mysqli_stmt_execute($stmt);
        }
        
        // Cuối cùng xóa store
        $query = "DELETE FROM stores WHERE store_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $store_id);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            $success = 'Đã xóa cửa hàng và tất cả dữ liệu liên quan thành công!';
        } else {
            throw new Exception('Không thể xóa cửa hàng!');
        }
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

// Lấy danh sách stores
$query = "SELECT * FROM stores ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$stores = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <title>Quản lý cửa hàng - TechBook Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <h2>Quản lý cửa hàng</h2>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Bảng danh sách cửa hàng -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên cửa hàng</th>
                        <th>Địa chỉ</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stores as $store): ?>
                    <tr>
                        <td><?php echo $store['store_id']; ?></td>
                        <td><?php echo htmlspecialchars($store['store_name']); ?></td>
                        <td><?php echo htmlspecialchars($store['address']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($store['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>">
                                <button type="submit" name="delete" class="btn-delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa cửa hàng này? Mọi đánh giá liên quan sẽ bị xóa!')">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>