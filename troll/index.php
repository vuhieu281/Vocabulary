<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "techbuc";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Get filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$rating = isset($_GET['rating']) ? $_GET['rating'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
$query = "SELECT r.*, u.username, s.name as store_name, s.address 
          FROM reviews r
          JOIN users u ON r.user_id = u.user_id
          JOIN stores s ON r.store_id = s.store_id
          WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (s.name LIKE :search OR s.address LIKE :search)";
}

if ($rating !== 'all') {
    $query .= " AND r.rating = :rating";
}

// Add sorting
switch ($sort) {
    case 'newest':
        $query .= " ORDER BY r.created_at DESC";
        break;
    case 'oldest':
        $query .= " ORDER BY r.created_at ASC";
        break;
    case '5':
    case '4':
    case '3':
    case '2':
    case '1':
        $query .= " ORDER BY r.rating DESC";
        break;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechBook</title>
    <link rel="stylesheet" href="./assets/css/index.css" />
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <h1><a href="./index.php">TechBook</a></h1>
            <nav class="nav">
                <a href="./index.php">TRANG CHỦ</a>
                <a href="./baidang.php">BÀI ĐĂNG</a>
                <a href="#">GIỚI THIỆU</a>
                <div class="dropdown">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="#" class="account">
                            TÀI KHOẢN 
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="./trangcanhan.php">
                                <i class="fas fa-user"></i>
                                Trang cá nhân
                            </a>
                            <a href="./thongtincanhan.php">
                                <i class="fas fa-cog"></i>
                                Cài đặt
                            </a>
                            <hr class="dropdown-divider">
                            <a href="logout.php" class="logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Đăng xuất
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="#" class="account">
                            TÀI KHOẢN 
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="./dangnhap.php">
                                <i class="fas fa-sign-in-alt"></i>
                                Đăng nhập
                            </a>
                            <a href="./dangky.php">
                                <i class="fas fa-user-plus"></i>
                                Đăng ký
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Body -->
    <div class="body">
        <div class="search-filter">
            <form method="GET" action="" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Tìm kiếm" value="<?php echo htmlspecialchars($search); ?>"/>
                    <select name="rating">
                        <option value="all" <?php echo $rating == 'all' ? 'selected' : ''; ?>>Tất cả</option>
                        <option value="5" <?php echo $rating == '5' ? 'selected' : ''; ?>>5 sao</option>
                        <option value="4" <?php echo $rating == '4' ? 'selected' : ''; ?>>4 sao</option>
                        <option value="3" <?php echo $rating == '3' ? 'selected' : ''; ?>>3 sao</option>
                        <option value="2" <?php echo $rating == '2' ? 'selected' : ''; ?>>2 sao</option>
                        <option value="1" <?php echo $rating == '1' ? 'selected' : ''; ?>>1 sao</option>
                        <option value="newest" <?php echo $rating == 'newest' ? 'selected' : ''; ?>> >Bài viết mới nhất</option>
                        <option value="oldest" <?php echo $rating == 'oldest' ? 'selected' : ''; ?>> >Bài viết cũ nhất</option>
                    </select>
                    <button type="submit">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <div class="reviews">
            <?php
            try {
                $stmt = $conn->prepare($query);
                
                if (!empty($search)) {
                    $searchParam = "%$search%";
                    $stmt->bindParam(':search', $searchParam);
                }
                
                if ($rating !== 'all') {
                    $stmt->bindParam(':rating', $rating);
                }
                
                $stmt->execute();
                $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($reviews as $review) {
                    echo '<div class="review-card">';
                    echo '<div class="stars">' . str_repeat('★', $review['rating']) . str_repeat('☆', 5-$review['rating']) . '</div>';
                    echo '<p class="title">' . htmlspecialchars($review['store_name']) . '</p>';
                    echo '<p>' . htmlspecialchars($review['username']) . '</p>';
                    echo '<p>' . date('d/m/Y', strtotime($review['created_at'])) . '</p>';
                    echo '</div>';
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="content">
            <div class="section">
                <h3>Về TechBook</h3>
                <p>Trang chuyên review cửa hàng đồ công nghệ secondhand uy tín tại Việt Nam</p>
                <p><i class="fas fa-envelope"></i> TechBook@gmail.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</p>
            </div>
            <div class="section">
                <h3>Hỗ trợ khách hàng</h3>
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
            </div>
            <div class="section">
                <h3>Chính sách</h3>
                <a href="#">Chính sách quyền riêng tư</a>
                <a href="#">Điều khoản & điều kiện</a>
            </div>
        </div>
        <div class="policy">
            <p>&copy; 2025 TechBook. Tất cả quyền được bảo lưu.</p>
        </div>
    </footer>
</body>
</html>