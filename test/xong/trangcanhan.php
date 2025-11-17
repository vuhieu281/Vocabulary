<?php
session_start();
require_once('config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php');
    exit();
}

// Get user information và reviews
$user_id = $_SESSION['user_id'];

// Thêm query để lấy thông tin user
$user_query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Query reviews
$reviews_query = "SELECT r.*, s.store_name, s.address,
                 (SELECT COUNT(*) FROM comments c WHERE c.review_id = r.review_id) as comment_count,
                 (SELECT AVG(rating) FROM comments c WHERE c.review_id = r.review_id) as avg_rating
                 FROM reviews r
                 JOIN stores s ON r.store_id = s.store_id
                 WHERE r.user_id = ?
                 ORDER BY r.created_at DESC";

$stmt = mysqli_prepare($conn, $reviews_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$reviews_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Previous Reviews</title>
    <link rel="stylesheet" href="assets/css/trangcanhan.css" />
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
    <!-- Header -->
    <?php 
    define('INCLUDED', true);
    include 'includes/header.php'; ?>

    <!-- Body -->
    <div class="container">
        <div class="other-reviews">
            <h3>Các bài đánh giá của bạn</h3>
            <div id="reviews-container">
                <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
                    <div id="review-<?php echo $review['review_id']; ?>" class="review-item">
                        <!-- Author info -->
                        <div class="review-header">
                            <span class="username"><?php echo htmlspecialchars($user['username']); ?></span>
                            <span class="review-date"><?php echo date('d/m/Y, H:i', strtotime($review['created_at'])); ?></span>
                        </div>

                        <!-- Review info -->
                        <h3 class="store-name"><?php echo htmlspecialchars($review['store_name']); ?></h3>
                        <div class="rating-section">
                            <span class="location"><?php echo htmlspecialchars($review['address']); ?></span>
                            <div class="stars">
                                <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                            </div>
                        </div>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($review['content'])); ?></p>

                        <?php
                        // Fetch review images
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

                        <!-- Comments section -->
                        <?php if($review['comment_count'] > 0): ?>
                            <div class="comments-section">
                                <h4>Bình luận (<?php echo $review['comment_count']; ?>)</h4>
                                <?php
                                $comments_query = "SELECT c.*, u.username FROM comments c 
                                                 JOIN users u ON c.user_id = u.user_id 
                                                 WHERE c.review_id = ? 
                                                 ORDER BY c.created_at DESC";
                                $stmt = mysqli_prepare($conn, $comments_query);
                                mysqli_stmt_bind_param($stmt, "i", $review['review_id']);
                                mysqli_stmt_execute($stmt);
                                $comments = mysqli_stmt_get_result($stmt);
                                ?>

                                <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
                                    <div class="comment">
                                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        <span class="comment-date"><?php echo date('d/m/Y, H:i', strtotime($comment['created_at'])); ?></span>
                                        <p class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/trangcanhan.js"></script>
</body>
</html>