<?php
<div class="review-item">
    <div class="review-header">
        <div class="reviewer-info">
            <h4 class="username"><?php echo htmlspecialchars($review['username']); ?></h4>
            <span class="review-date"><?php echo date('d/m/Y, H:i', strtotime($review['created_at'])); ?></span>
        </div>
    </div>
    <div class="review-content">
        <h3 class="store-name"><?php echo htmlspecialchars($review['store_name']); ?></h3>
        <p class="address"><?php echo htmlspecialchars($review['address']); ?></p>
        <div class="stars">
            <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
        </div>
        <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
    </div>
    
    <?php
    $query = "SELECT image_url FROM review_images WHERE review_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $review['review_id']);
    mysqli_stmt_execute($stmt);
    $images_result = mysqli_stmt_get_result($stmt);
    ?>

    <?php if(mysqli_num_rows($images_result) > 0): ?>
        <div class="review-images">
            <?php while ($image = mysqli_fetch_assoc($images_result)): ?>
                <div class="review-image">
                    <img src="<?php echo htmlspecialchars('./uploads/' . $image['image_url']); ?>" 
                         alt="Ảnh đính kèm"
                         onerror="this.src='assets/images/image-not-found.png';" />
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <div class="review-stats">
        <span>Tổng bình luận: <?php echo $review['comment_count']; ?></span>
    </div>
    
    <?php include 'includes/comments.php'; ?>
</div>