<?php
require_once('auth.php');
require_once('../config/db.php');

$error = '';
$success = '';

// Xử lý xóa review
if (isset($_POST['delete'])) {
    $review_id = (int)$_POST['review_id'];
    
    try {
        mysqli_begin_transaction($conn);
        
        // 1. Xóa tất cả ảnh của review
        $query = "DELETE FROM review_images WHERE review_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $review_id);
        mysqli_stmt_execute($stmt);
        
        // 2. Xóa tất cả comments của review
        $query = "DELETE FROM comments WHERE review_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $review_id);
        mysqli_stmt_execute($stmt);
        
        // 3. Lấy store_id của review để kiểm tra
        $query = "SELECT store_id FROM reviews WHERE review_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $review_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $store = mysqli_fetch_assoc($result);
        
        // 4. Xóa review
        $query = "DELETE FROM reviews WHERE review_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $review_id);
        mysqli_stmt_execute($stmt);
        
        // 5. Kiểm tra xem cửa hàng còn review nào không
        if ($store) {
            $query = "SELECT COUNT(*) as count FROM reviews WHERE store_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $store['store_id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $review_count = mysqli_fetch_assoc($result)['count'];
            
            // Nếu không còn review nào, xóa cửa hàng
            if ($review_count == 0) {
                $query = "DELETE FROM stores WHERE store_id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $store['store_id']);
                mysqli_stmt_execute($stmt);
            }
        }
        
        mysqli_commit($conn);
        $success = 'Đã xóa đánh giá và tất cả dữ liệu liên quan!';
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

// Lấy danh sách reviews
$query = "SELECT r.*, u.username, s.store_name 
          FROM reviews r 
          JOIN users u ON r.user_id = u.user_id 
          JOIN stores s ON r.store_id = s.store_id 
          ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đánh giá - TechBook Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <h2>Quản lý đánh giá</h2>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Cửa hàng</th>
                        <th>Đánh giá</th>
                        <th>Nội dung</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo $review['review_id']; ?></td>
                        <td><?php echo htmlspecialchars($review['username']); ?></td>
                        <td><?php echo htmlspecialchars($review['store_name']); ?></td>
                        <td><?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></td>
                        <td><?php echo htmlspecialchars(substr($review['content'], 0, 100)) . '...'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                <button type="submit" name="delete" class="btn-delete" 
                                        onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
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