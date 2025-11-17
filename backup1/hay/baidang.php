<?php
session_start();
require_once('config/db.php');

// Define INCLUDED once at the beginning
define('INCLUDED', true);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php');
    exit();
}

$error = '';
$success = '';

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_name = mysqli_real_escape_string($conn, $_POST['store_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = $_SESSION['user_id'];

    // Validate inputs
    if (empty($store_name) || empty($address) || $rating < 1 || empty($comment)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else {
        // Handle file upload
        $images = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $upload_dir = __DIR__ . '/uploads/'; // Sử dụng đường dẫn tuyệt đối
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    die('Không thể tạo thư mục uploads');
                }
                chmod($upload_dir, 0777); // Cấp quyền ghi
            }
            
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp_name = $files['tmp_name'][$i];
                    $name = basename($files['name'][$i]);
                    // Tạo tên file duy nhất
                    $file_name = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $name);
                    $target_path = $upload_dir . $file_name;
                    
                    // Kiểm tra và upload file
                    if (is_uploaded_file($tmp_name)) {
                        if (move_uploaded_file($tmp_name, $target_path)) {
                            $images[] = $file_name;
                        } else {
                            $error = 'Không thể upload file: ' . $name;
                            break;
                        }
                    }
                }
            }
        }

        // Kiểm tra user_id trước khi thêm review
        if (!isset($_SESSION['user_id'])) {
            $error = 'Vui lòng đăng nhập để đăng bài đánh giá';
        } else {
            $user_id = $_SESSION['user_id'];
            
            // Kiểm tra user có tồn tại trong database
            $check_user = "SELECT user_id FROM users WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $check_user);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $user_result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($user_result) === 0) {
                $error = 'Người dùng không tồn tại';
            } else {
                // Insert store first
                $query = "INSERT INTO stores (store_name, address) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ss", $store_name, $address);
                
                if (mysqli_stmt_execute($stmt)) {
                    $store_id = mysqli_insert_id($conn);
                    
                    // Then insert review
                    $query = "INSERT INTO reviews (user_id, store_id, rating, content) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "iiis", $user_id, $store_id, $rating, $comment);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $review_id = mysqli_insert_id($conn);
                        
                        // Save images paths
                        if (!empty($images)) {
                            foreach ($images as $image) {
                                $query = "INSERT INTO review_images (review_id, image_url) VALUES (?, ?)";
                                $stmt = mysqli_prepare($conn, $query);
                                mysqli_stmt_bind_param($stmt, "is", $review_id, $image);
                                mysqli_stmt_execute($stmt);
                            }
                        }
                        
                        $success = 'Đăng bài đánh giá thành công!';
                    } else {
                        $error = 'Có lỗi xảy ra khi thêm đánh giá';
                    }
                } else {
                    $error = 'Có lỗi xảy ra khi thêm cửa hàng';
                }
            }
        }
    }
}

// Thay thế phần query cũ
$query = "SELECT r.review_id, s.store_name, s.address,
          r.content as comment, r.created_at, r.rating,
          u.username, u.user_id,
          (SELECT COUNT(*) FROM comments c WHERE c.review_id = r.review_id) as comment_count
          FROM reviews r
          JOIN users u ON r.user_id = u.user_id
          JOIN stores s ON r.store_id = s.store_id
          ORDER BY r.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$reviews = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

// Hiển thị chi tiết bài viết nếu có id
if (isset($_GET['id'])) {
    $review_id = (int)$_GET['id'];
    $query = "SELECT r.*, s.store_name, s.address, u.username
              FROM reviews r
              JOIN stores s ON r.store_id = s.store_id
              JOIN users u ON r.user_id = u.user_id
              WHERE r.review_id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $review_id);
    mysqli_stmt_execute($stmt);
    $review = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($review) {
        // Hiển thị chi tiết bài viết
        echo '
        <div class="review-detail">
            <h2>' . htmlspecialchars($review['store_name']) . '</h2>
            <p class="address">' . htmlspecialchars($review['address']) . '</p>
            <div class="stars">
                ' . str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) . '
            </div>
            <p class="review-content">' . nl2br(htmlspecialchars($review['content'])) . '</p>
            <div class="review-meta">
                <span class="author">' . htmlspecialchars($review['username']) . '</span>
                <span class="date">' . date('d/m/Y, H:i', strtotime($review['created_at'])) . '</span>
            </div>';

        // Hiển thị ảnh
        $query = "SELECT image_url FROM review_images WHERE review_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $review_id);
        mysqli_stmt_execute($stmt);
        $images = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($images) > 0) {
            echo '<div class="review-images">';
            while ($image = mysqli_fetch_assoc($images)) {
                echo '<div class="review-image">
                        <img src="./uploads/' . htmlspecialchars($image['image_url']) . '" 
                             alt="Ảnh đính kèm"
                             onerror="this.src=\'assets/images/image-not-found.png\';" />
                    </div>';
            }
            echo '</div>';
        }

        echo '</div>';

        // Hiển thị phần comments
        include 'includes/comments.php';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đánh giá cửa hàng - TechBook</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container animate-fade-in">
        <div class="card review-form">
            <h2 style="text-align:center;">Đăng bài đánh giá</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST" action="baidang.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="store_name" class="form-control" placeholder="Tên cửa hàng" required />
                </div>
                <div class="form-group">
                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required />
                </div>
                <div class="form-group">
                    <label>Đánh giá:</label>
                    <div class="rating-container">
                        <div class="stars">
                            <input type="radio" name="rating" id="star5" value="5" required><label for="star5" title="5 sao">★</label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="4 sao">★</label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="3 sao">★</label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="2 sao">★</label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="1 sao">★</label>
                        </div>
                        <span class="rating-text">Chọn đánh giá của bạn</span>
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="comment" class="form-control" placeholder="Nhận xét" required></textarea>
                </div>
                <div class="form-group">
                    <label>Hình ảnh đính kèm:</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control" />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Gửi</button>
            </form>
        </div>
        <div class="other-reviews">
            <h3 style="text-align:center;">Các bài đánh giá khác</h3>
            <div id="reviews-container" class="reviews">
                <?php foreach ($reviews as $review): ?>
                    <div id="review-<?php echo $review['review_id']; ?>" class="review-card card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <h4 class="author"><?php echo htmlspecialchars($review['username']); ?></h4>
                                <span class="date"><?php echo date('d/m/Y, H:i', strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="review-content">
                            <h3 class="store-name"><?php echo htmlspecialchars($review['store_name']); ?></h3>
                            <p class="store-address"><?php echo htmlspecialchars($review['address']); ?></p>
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
                                        <img src="<?php echo htmlspecialchars('./uploads/' . $image['image_url']); ?>" alt="Ảnh đính kèm" onerror="this.src='assets/images/image-not-found.png';" />
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                        <div class="comments-section">
                            <div class="comments-title">Bình luận</div>
                            <div class="comments-container">
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
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/baidang.js" defer></script>
</body>
</html>