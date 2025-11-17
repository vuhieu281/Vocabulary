<?php
session_start();
require_once './model/pdo.php';

try {
    $conn = pdo_get_connection();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "techbuc";
session_start();

// Handle form submission for new review
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $errors = [];
    
    if (empty($_POST['store_name'])) {
        $errors[] = "Vui lòng nhập tên cửa hàng";
    }
    
    if (empty($_POST['address'])) {
        $errors[] = "Vui lòng nhập địa chỉ";
    }
    
    if (empty($_POST['rating']) || !is_numeric($_POST['rating']) || $_POST['rating'] < 1 || $_POST['rating'] > 5) {
        $errors[] = "Vui lòng chọn số sao đánh giá";
    }
    
    if (empty($_POST['content'])) {
        $errors[] = "Vui lòng nhập nội dung đánh giá";
    }
    
    if (empty($errors)) {
        $store_name = trim($_POST['store_name']);
        $address = trim($_POST['address']);
        $rating = $_POST['rating'];
        $content = trim($_POST['content']);
        $user_id = $_SESSION['user_id'] ?? null;

        // Handle file upload
        $image = null;
        if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['review_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = uniqid() . '.' . $ext;
                $upload_path = 'uploads/' . $new_filename;
                
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                if (move_uploaded_file($_FILES['review_image']['tmp_name'], $upload_path)) {
                    $image = $upload_path;
                } else {
                    $error = "Lỗi khi upload file";
                }
            } else {
                $error = "Định dạng file không được hỗ trợ";
            }
        }

        if ($user_id) {
            try {
                $sql = "INSERT INTO reviews (user_id, store_name, address, rating, content, image) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$user_id, $store_name, $address, $rating, $content, $image]);
                header("Location: baidang.php?success=1");
                exit();
            } catch(PDOException $e) {
                $error = "Lỗi: " . $e->getMessage();
            }
        } else {
            header("Location: dangnhap.php");
            exit();
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Get existing reviews
try {
    $sql = "SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Lỗi: " . $e->getMessage();
    $reviews = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đánh giá cửa hàng</title>
    <link rel="stylesheet" href="./assets/css/baidang.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a href="#" class="account">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
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
                    </div>
                <?php else: ?>
                    <a href="./dangnhap.php" class="account">ĐĂNG NHẬP</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Body -->
    <div class="container">
        <!-- Form đánh giá mới -->
        <div class="review-form">
            <h2>Đăng bài đánh giá</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="store_name" placeholder="Tên cửa hàng" required />
                </div>
                <div class="form-group">
                    <input type="text" name="address" placeholder="Địa chỉ" required />
                </div>
                <div class="form-group">
                    <label>Đánh giá sao:</label>
                    <div class="rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="far fa-star" data-rating="<?php echo $i; ?>"></i>
                        <?php endfor; ?>
                        <input type="hidden" name="rating" id="rating-value" value="0" required />
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="content" placeholder="Nhận xét" required></textarea>
                </div>
                <div class="form-group">
                    <input type="file" name="review_image" id="review_image" hidden />
                    <button type="button" class="btn-file" onclick="document.getElementById('review_image').click()">
                        Chọn tệp
                    </button>
                    <span class="file-info">Không tệp nào được chọn</span>
                </div>
                <button type="submit" name="submit_review" class="btn-submit">Gửi</button>
            </form>
        </div>

        <!-- Phần hiển thị đánh giá khác -->
        <div class="other-reviews">
            <h3>Các bài đánh giá khác</h3>
            <?php foreach($reviews as $review): ?>
                <div class="review-item" id="review-<?php echo $review['review_id']; ?>">
                    <div class="reviewer-info">
                        <img src="<?php echo !empty($review['avatar']) ? $review['avatar'] : 'avatar-placeholder.png'; ?>" 
                             alt="<?php echo htmlspecialchars($review['username']); ?>" />
                        <div>
                            <h4><?php echo htmlspecialchars($review['username']); ?></h4>
                            <span class="review-date">
                                <?php echo date('d/m/Y, H:i', strtotime($review['created_at'])); ?>
                            </span>
                        </div>
                    </div>

                    <h4><?php echo htmlspecialchars($review['store_name']); ?></h4>
                    <p class="address"><?php echo htmlspecialchars($review['address']); ?></p>

                    <div class="rating">
                        <?php
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $review['rating']) {
                                echo '<i class="fas fa-star"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>

                    <p class="review-text">
                        <?php echo nl2br(htmlspecialchars($review['content'])); ?>
                    </p>

                    <?php if(!empty($review['image'])): ?>
                        <div class="review-image">
                            <img src="<?php echo htmlspecialchars($review['image']); ?>" 
                                 alt="Ảnh đính kèm" />
                        </div>
                    <?php endif; ?>

                    <!-- Comments section -->
                    <?php
                    try {
                        $comment_sql = "SELECT c.*, u.username 
                                        FROM comments c 
                                        JOIN users u ON c.user_id = u.user_id 
                                        WHERE c.review_id = :review_id 
                                        ORDER BY c.created_at DESC";
                        $stmt = $conn->prepare($comment_sql);
                        $stmt->bindParam(':review_id', $review['review_id']);
                        $stmt->execute();
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch(PDOException $e) {
                        $comments = [];
                    }
                    ?>

                    <div class="comments">
                        <?php foreach($comments as $comment): ?>
                            <div class="comment">
                                <h5><?php echo htmlspecialchars($comment['username']); ?></h5>
                                <p><?php echo htmlspecialchars($comment['content']); ?></p>
                                <div class="rating">
                                    <?php
                                    for($i = 1; $i <= 5; $i++) {
                                        if($i <= $comment['rating']) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="comment-form">
                            <form method="POST" action="add_comment.php">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>" />
                                <h5>Đánh giá của bạn:</h5>
                                <div class="rating comment-rating">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="far fa-star" data-rating="<?php echo $i; ?>"></i>
                                    <?php endfor; ?>
                                    <input type="hidden" name="rating" value="0" required />
                                </div>
                                <textarea name="content" placeholder="Viết bình luận..." required></textarea>
                                <button type="submit" class="btn-submit">Gửi</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Star rating functionality
        document.querySelectorAll('.rating').forEach(function(ratingGroup) {
            const stars = ratingGroup.querySelectorAll('.fa-star');
            const input = ratingGroup.nextElementSibling;

            stars.forEach(function(star, index) {
                star.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    input.value = rating;
                    
                    stars.forEach(function(s, i) {
                        if (i < rating) {
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                });
            });
        });

        // File upload preview
        document.getElementById('review_image').addEventListener('change', function() {
            const fileInfo = document.querySelector('.file-info');
            if (this.files && this.files[0]) {
                fileInfo.textContent = this.files[0].name;
            } else {
                fileInfo.textContent = 'Không tệp nào được chọn';
            }
        });
    </script>
</body>
</html>