<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Get the latest reviews
$sql = "SELECT r.*, s.name as service_name, u.username, u.profile_image 
        FROM reviews r 
        JOIN services s ON r.service_id = s.id 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC 
        LIMIT 9";
$result = mysqli_query($conn, $sql);
?>

<div class="search-section">
    <form action="search.php" method="GET">
        <input type="text" name="q" class="search-box" placeholder="Tìm kiếm dịch vụ...">
    </form>
</div>

<div class="reviews-grid">
    <?php 
    if ($result && mysqli_num_rows($result) > 0):
        while ($review = mysqli_fetch_assoc($result)): 
    ?>
        <div class="review-card">
            <div class="review-header">
                <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-o'; ?>"></i>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="review-body">
                <p><?php echo htmlspecialchars(substr($review['content'], 0, 150)) . '...'; ?></p>
            </div>
            <div class="reviewer">
                <img src="<?php echo $review['profile_image'] ?: '/assets/images/default-avatar.png'; ?>" 
                     alt="<?php echo htmlspecialchars($review['username']); ?>" 
                     class="reviewer-avatar">
                <span class="reviewer-name"><?php echo htmlspecialchars($review['username']); ?></span>
            </div>
        </div>
    <?php 
        endwhile;
    else:
    ?>
        <div class="no-reviews">
            <h3>Chưa có đánh giá nào</h3>
            <p>Hãy là người đầu tiên đánh giá dịch vụ!</p>
            <?php if ($logged_in): ?>
                <a href="/add-review.php" class="btn btn-primary">Thêm đánh giá</a>
            <?php else: ?>
                <p>Vui lòng <a href="/login.php">đăng nhập</a> để thêm đánh giá</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($result && mysqli_num_rows($result) > 0): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= 3; $i++): ?>
        <a href="?page=<?php echo $i; ?>" <?php echo isset($_GET['page']) && $_GET['page'] == $i ? 'class="active"' : ''; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
    <a href="#">...</a>
    <a href="?page=67">67</a>
    <a href="?page=68">68</a>
    <a href="?page=2">Next »</a>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?> 