<?php
if(!defined('INCLUDED')) {
    header('Location: ../index.php');
    exit;
}
?>

<div class="comments-section">
    <?php
    // Query tất cả comments của review
    $comments_query = "SELECT c.*, u.username 
                      FROM comments c 
                      JOIN users u ON c.user_id = u.user_id 
                      WHERE c.review_id = ?
                      ORDER BY c.created_at DESC";
    $stmt = mysqli_prepare($conn, $comments_query);
    mysqli_stmt_bind_param($stmt, "i", $review['review_id']);
    mysqli_stmt_execute($stmt);
    $comments = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
    ?>

    <div class="comments-container">
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                    <span class="comment-date"><?php echo date('d/m/Y, H:i', strtotime($comment['created_at'])); ?></span>
                </div>
                <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Form thêm bình luận mới -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form class="comment-form" method="POST" action="comment.php">
            <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
            <div class="form-group">
                <textarea name="comment_content" placeholder="Viết bình luận của bạn..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">Gửi bình luận</button>
        </form>
    <?php else: ?>
        <p class="login-prompt">Vui lòng <a href="dangnhap.php">đăng nhập</a> để bình luận.</p>
    <?php endif; ?>
</div>