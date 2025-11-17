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
                            <div class="review-actions">
                                <button type="button" class="edit-btn" onclick="showEditForm(<?php echo $review['review_id']; ?>)">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                            </div>
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

                        <!-- Add edit form (initially hidden) -->
                        <div id="edit-form-<?php echo $review['review_id']; ?>" class="edit-form" style="display:none;">
                            <form id="editReviewForm" method="POST" action="update_review.php">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                <div class="form-group">
                                    <label for="store_name-<?php echo $review['review_id']; ?>">Tên cửa hàng:</label>
                                    <input type="text" name="store_name" id="store_name-<?php echo $review['review_id']; ?>" 
                                           value="<?php echo htmlspecialchars($review['store_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="address-<?php echo $review['review_id']; ?>">Địa chỉ:</label>
                                    <input type="text" name="address" id="address-<?php echo $review['review_id']; ?>" 
                                           value="<?php echo htmlspecialchars($review['address']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Đánh giá:</label>
                                    <div class="rating-container">
                                        <div class="stars">
                                            <input type="radio" name="rating" id="edit_star5" value="5" required>
                                            <label for="edit_star5" title="Rất tốt">★</label>
                                            <input type="radio" name="rating" id="edit_star4" value="4">
                                            <label for="edit_star4" title="Tốt">★</label>
                                            <input type="radio" name="rating" id="edit_star3" value="3">
                                            <label for="edit_star3" title="Bình thường">★</label>
                                            <input type="radio" name="rating" id="edit_star2" value="2">
                                            <label for="edit_star2" title="Tệ">★</label>
                                            <input type="radio" name="rating" id="edit_star1" value="1">
                                            <label for="edit_star1" title="Rất tệ">★</label>
                                        </div>
                                        <span class="rating-text">Chọn đánh giá của bạn</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content-<?php echo $review['review_id']; ?>">Nội dung:</label>
                                    <textarea name="content" id="content-<?php echo $review['review_id']; ?>" required><?php echo htmlspecialchars($review['content']); ?></textarea>
                                </div>
                                <div class="button-group">
                                    <button type="submit" class="btn-save">
                                        <i class="fas fa-save"></i>
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>

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