<?php
if(!defined('INCLUDED')) {
    header('Location: ../index.php');
    exit;
}

// Get comments for current review
$comments_query = "SELECT c.*, u.username 
                  FROM comments c
                  JOIN users u ON c.user_id = u.user_id 
                  WHERE c.review_id = ?
                  ORDER BY c.created_at DESC";
$stmt = mysqli_prepare($conn, $comments_query);
mysqli_stmt_bind_param($stmt, "i", $review['review_id']);
mysqli_stmt_execute($stmt);
$comments_result = mysqli_stmt_get_result($stmt);
?>

<div class="comments-section">
    <h4>Bình luận (<?php echo $review['comment_count']; ?>)</h4>
    <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
        <div class="comment">
            <div class="comment-header">
                <h5><?php echo htmlspecialchars($comment['username']); ?></h5>
                <span class="comment-date">
                    <?php echo date('d/m/Y, H:i', strtotime($comment['created_at'])); ?>
                </span>
            </div>
            <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
        </div>
    <?php endwhile; ?>
</div>