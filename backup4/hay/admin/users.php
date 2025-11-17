<?php
require_once('auth.php');
require_once('../config/db.php');

$error = '';
$success = '';

// Xử lý xóa user
if (isset($_POST['delete'])) {
    $user_id = (int)$_POST['user_id'];
    
    try {
        mysqli_begin_transaction($conn);
        
        // 1. Xóa comments của user
        $query = "DELETE FROM comments WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        // 2. Xóa review_images từ reviews của user
        $query = "DELETE ri FROM review_images ri 
                 INNER JOIN reviews r ON ri.review_id = r.review_id 
                 WHERE r.user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        // 3. Xóa comments trên reviews của user
        $query = "DELETE c FROM comments c 
                 INNER JOIN reviews r ON c.review_id = r.review_id 
                 WHERE r.user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        // 4. Xóa reviews của user
        $query = "DELETE FROM reviews WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        // 5. Cuối cùng xóa user
        $query = "DELETE FROM users WHERE user_id = ? AND role = 0";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        mysqli_commit($conn);
        $success = 'Đã xóa người dùng và tất cả dữ liệu liên quan!';
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

// Lấy danh sách users
$query = "SELECT * FROM users WHERE role = 0 ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng - TechBook Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <h2>Quản lý người dùng</h2>
        
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
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <button type="submit" name="delete" class="btn-delete" 
                                        onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
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