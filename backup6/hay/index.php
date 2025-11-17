<?php
session_start();
require_once('config/db.php');

define('INCLUDED', true);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

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

$query .= " LIMIT $offset, $perPage";

$result = mysqli_query($conn, $query);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);

$totalQuery = "SELECT COUNT(*) as total FROM reviews";
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
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    <main>
        <!-- Banner Section -->
        <div class="banner">
            <div class="banner-content">
                <h1>Chia sẻ trải nghiệm của bạn</h1>
                <p>Khám phá và đánh giá các cửa hàng công nghệ tốt nhất</p>
            </div>
        </div>

        <!-- Body -->
        <div class="body">
            <div class="search-filter">
                <form method="GET" action="index.php" class="search-form">
                    <div class="search-input">
                        <input type="text" name="search" placeholder="Tìm kiếm cửa hàng, đánh giá..." value="<?php echo htmlspecialchars($search); ?>" />
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="filter-container">
                        <select name="filter" onchange="this.form.submit()">
                            <option value="">Tất cả</option>
                            <option value="5" <?php echo $filter === '5' ? 'selected' : ''; ?>>5 sao</option>
                            <option value="4" <?php echo $filter === '4' ? 'selected' : ''; ?>>4 sao</option>
                            <option value="3" <?php echo $filter === '3' ? 'selected' : ''; ?>>3 sao</option>
                            <option value="2" <?php echo $filter === '2' ? 'selected' : ''; ?>>2 sao</option>
                            <option value="1" <?php echo $filter === '1' ? 'selected' : ''; ?>>1 sao</option>
                            <option value="newest" <?php echo $filter === 'newest' ? 'selected' : ''; ?>>Bài viết mới nhất</option>
                            <option value="oldest" <?php echo $filter === 'oldest' ? 'selected' : ''; ?>>Bài viết cũ nhất</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="reviews">
                <?php foreach ($reviews as $index => $review): ?>
                    <a href="baidang.php#review-<?php echo $review['review_id']; ?>" class="review-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="stars">
                            <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                        </div>
                        <h3 class="store-name"><?php echo htmlspecialchars($review['store_name']); ?></h3>
                        <p class="store-address"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($review['address']); ?></p>
                        <p class="review-content"><?php 
                            echo htmlspecialchars(strlen($review['content']) > 100 ? 
                                 substr($review['content'], 0, 100) . '...' : 
                                 $review['content']); 
                        ?></p>
                        <div class="review-meta">
                            <span class="author"><i class="fas fa-user"></i> <?php echo htmlspecialchars($review['username']); ?></span>
                            <span class="date"><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($review['created_at'])); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">
                        <i class="fas fa-chevron-left"></i> Trang trước
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $page): ?>
                        <a href="#" class="active"><?php echo $i; ?></a>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">
                        Trang sau <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/index.js"></script>
</body>
</html>