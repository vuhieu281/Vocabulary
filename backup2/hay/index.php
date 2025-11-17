<?php
session_start();
require_once('config/db.php');

// Define INCLUDED once at the beginning
define('INCLUDED', true);

// Fetch reviews from database
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Trang hiện tại
$perPage = 9;  // Số bài viết mỗi trang  
$offset = ($page - 1) * $perPage;  // Vị trí bắt đầu lấy dữ liệu

// Search and filter functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

// Build query based on filters
$query = "SELECT DISTINCT r.review_id, r.content, r.rating, r.created_at, 
          u.username, s.store_name, s.address 
          FROM reviews r 
          JOIN users u ON r.user_id = u.user_id
          JOIN stores s ON r.store_id = s.store_id 
          LEFT JOIN comments c ON r.review_id = c.review_id
          LEFT JOIN users comment_user ON c.user_id = comment_user.user_id
          WHERE 1=1";

// Thêm điều kiện search
if (!empty($search)) {
    $query .= " AND (s.store_name LIKE '%$search%' 
                OR r.content LIKE '%$search%' 
                OR s.address LIKE '%$search%'
                OR u.username LIKE '%$search%'
                OR comment_user.username LIKE '%$search%')";
    // Khi có tìm kiếm, sắp xếp theo rating từ cao đến thấp
    $query .= " ORDER BY r.rating DESC, r.created_at DESC";
} else {
    // Khi không có tìm kiếm, áp dụng các filter như bình thường
    if ($filter === 'newest') {
        $query .= " ORDER BY r.created_at DESC";
    } elseif ($filter === 'oldest') {
        $query .= " ORDER BY r.created_at ASC";
    } elseif (is_numeric($filter)) {
        $query .= " AND r.rating = $filter ORDER BY r.created_at DESC";
    } else {
        // Mặc định sắp xếp theo thời gian mới nhất
        $query .= " ORDER BY r.created_at DESC";
    }
}

// Thêm LIMIT cho phân trang
$query .= " LIMIT $offset, $perPage";

// Lấy danh sách reviews
$result = mysqli_query($conn, $query);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Đếm tổng số reviews cho phân trang
$totalQuery = "SELECT COUNT(DISTINCT r.review_id) as total 
               FROM reviews r 
               JOIN users u ON r.user_id = u.user_id
               JOIN stores s ON r.store_id = s.store_id 
               LEFT JOIN comments c ON r.review_id = c.review_id
               LEFT JOIN users comment_user ON c.user_id = comment_user.user_id
               WHERE 1=1";

// Thêm điều kiện search vào totalQuery nếu có
if (!empty($search)) {
    $totalQuery .= " AND (s.store_name LIKE '%$search%' 
                    OR r.content LIKE '%$search%' 
                    OR s.address LIKE '%$search%'
                    OR u.username LIKE '%$search%'
                    OR comment_user.username LIKE '%$search%')";
}

$totalResult = mysqli_query($conn, $totalQuery);
$total = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechBook</title>
    <link rel="stylesheet" href="assets/css/index.css" />
    <link rel="stylesheet" href="assets/css/dangky.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h2>TechBook</h2>
            <p>Khám phá và đánh giá các cửa hàng công nghệ secondhand uy tín</p>
            <a href="baidang.php" class="hero-btn">Đăng bài ngay</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-star"></i>
                    <h3>Đánh giá chân thực</h3>
                    <p>Từ cộng đồng người dùng thực tế</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Tin cậy</h3>
                    <p>Thông tin được xác thực kỹ lưỡng</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-sync-alt"></i>
                    <h3>Cập nhật liên tục</h3>
                    <p>Thông tin mới nhất về các cửa hàng</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <div class="search-filter-section">
        <div class="container">
            <form method="GET" action="" class="search-filter">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Tìm kiếm cửa hàng..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <select name="filter" onchange="this.form.submit()">
                    <option value="">Tất cả đánh giá</option>
                    <option value="5" <?php echo isset($_GET['filter']) && $_GET['filter'] === '5' ? 'selected' : ''; ?>>5 sao</option>
                    <option value="4" <?php echo isset($_GET['filter']) && $_GET['filter'] === '4' ? 'selected' : ''; ?>>4 sao</option>
                    <option value="3" <?php echo isset($_GET['filter']) && $_GET['filter'] === '3' ? 'selected' : ''; ?>>3 sao</option>
                    <option value="2" <?php echo isset($_GET['filter']) && $_GET['filter'] === '2' ? 'selected' : ''; ?>>2 sao</option>
                    <option value="1" <?php echo isset($_GET['filter']) && $_GET['filter'] === '1' ? 'selected' : ''; ?>>1 sao</option>
                    <option value="newest" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'newest' ? 'selected' : ''; ?>>Mới nhất</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <div class="container">
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <h3><?php echo htmlspecialchars($review['store_name']); ?></h3>
                        <div class="rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="review-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars($review['address']); ?>
                    </p>
                    <p class="review-content"><?php echo nl2br(htmlspecialchars($review['content'])); ?></p>
                    
                    <?php if (!empty($review['images'])): ?>
                    <div class="review-images">
                        <?php foreach ($review['images'] as $image): ?>
                        <img src="uploads/<?php echo htmlspecialchars($image); ?>" 
                             alt="Review image"
                             onclick="openImageModal(this.src)">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="review-footer">
                        <div class="review-author">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($review['username']); ?>
                        </div>
                        <div class="review-date">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo date('d/m/Y', strtotime($review['created_at'])); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo ($current_page - 1); ?><?php echo $filter_query; ?>">&laquo; Trước</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $filter_query; ?>" 
                       class="<?php echo $i === $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo ($current_page + 1); ?><?php echo $filter_query; ?>">Sau &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/index.js"></script>
</body>
</html>