<?php
require_once('auth.php');
require_once('../config/db.php');

$error = '';
$success = '';

// Xử lý xóa comment
if (isset($_POST['delete'])) {
    $comment_id = (int)$_POST['comment_id'];
    $query = "DELETE FROM comments WHERE comment_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $comment_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Đã xóa bình luận thành công!';
    } else {
        $error = 'Không thể xóa bình luận!';
    }
}

// Lấy danh sách comments
$query = "SELECT c.*, u.username, r.content as review_content 
          FROM comments c
          JOIN users u ON c.user_id = u.user_id
          JOIN reviews r ON c.review_id = r.review_id
          ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bình luận - TechBook Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <h2>Quản lý bình luận</h2>
        
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
                        <th>Đánh giá gốc</th>
                        <th>Nội dung</th>
                        <th>Rating</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo $comment['comment_id']; ?></td>
                        <td><?php echo htmlspecialchars($comment['username']); ?></td>
                        <td><?php echo htmlspecialchars(substr($comment['review_content'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($comment['content']); ?></td>
                        <td><?php echo str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']); ?></td>
                        <td><?php echo date('d/m/Y, H:i', strtotime($comment['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                <button type="submit" name="delete" class="btn-delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
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