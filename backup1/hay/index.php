<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
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

// Build query based on filters - Sửa câu query để thêm LEFT JOIN với bảng comments
$query = "SELECT DISTINCT r.review_id, r.content, r.rating, r.created_at, 
          u.username, s.store_name, s.address 
          FROM reviews r 
          JOIN users u ON r.user_id = u.user_id
          JOIN stores s ON r.store_id = s.store_id 
          LEFT JOIN comments c ON r.review_id = c.review_id
          LEFT JOIN users comment_user ON c.user_id = comment_user.user_id
          WHERE 1=1";

// Thêm điều kiện search - Sửa phần tìm kiếm để bao gồm cả người comment
if (!empty($search)) {
    $query .= " AND (s.store_name LIKE '%$search%' 
                OR r.content LIKE '%$search%' 
                OR s.address LIKE '%$search%'
                OR u.username LIKE '%$search%'
                OR comment_user.username LIKE '%$search%')";
}

// Thêm điều kiện filter
if ($filter === 'newest') {
    $query .= " ORDER BY r.created_at DESC";
} elseif ($filter === 'oldest') {
    $query .= " ORDER BY r.created_at ASC";
} elseif (is_numeric($filter)) {
    $query .= " AND r.rating = $filter ORDER BY r.created_at DESC";
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
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta name="description" content="TechBook - Nền tảng đánh giá và chia sẻ trải nghiệm về các cửa hàng công nghệ" />
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <!-- Banner -->
    <div class="banner">
        <img src="assets/images/banner.avif" alt="TechBook Banner" />
        <div class="banner-content">
            <h2>Chào mừng đến với TechBook!</h2>
            <p>Khám phá, đánh giá và chia sẻ trải nghiệm về các cửa hàng công nghệ.</p>
        </div>
    </div>
    <div class="body animate-fade-in">
        <div class="search-filter card">
            <form method="GET" action="index.php" class="search-form">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <input type="text" 
                           name="search" 
                           class="form-control"
                           placeholder="Tìm kiếm cửa hàng, địa chỉ hoặc nội dung..." 
                           value="<?php echo htmlspecialchars($search); ?>" />
                </div>
                <div class="form-group">
                    <select name="filter" class="form-control" onchange="this.form.submit()">
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
            <?php foreach ($reviews as $review): ?>
                <a href="baidang.php#review-<?php echo $review['review_id']; ?>" class="card review-card">
                    <div class="stars">
                        <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                    </div>
                    <h3 class="store-name"><?php echo htmlspecialchars($review['store_name']); ?></h3>
                    <p class="store-address"><?php echo htmlspecialchars($review['address']); ?></p>
                    <p class="review-content"><?php 
                        echo htmlspecialchars(strlen($review['content']) > 100 ? 
                             substr($review['content'], 0, 100) . '...' : 
                             $review['content']); 
                    ?></p>
                    <div class="review-meta">
                        <span class="author"><?php echo htmlspecialchars($review['username']); ?></span>
                        <span class="date"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>" class="btn btn-primary">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <a href="#" class="btn btn-primary active"><?php echo $i; ?></a>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>" class="btn btn-primary"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>" class="btn btn-primary">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js" defer></script>
</body>
</html>