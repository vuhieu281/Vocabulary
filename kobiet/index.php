<?php
session_start();
include 'includes/header.php';
?>

<div class="review-grid">
    <?php 
    // Sample review data
    $reviews = [
        [
            'title' => 'Review cửa hàng ABC',
            'body' => 'Cửa hàng bán nhiều đồ điện tử cũ chất lượng tốt...',
            'rating' => 5,
            'reviewer' => 'Người dùng 1',
            'reviewer_image' => 'https://via.placeholder.com/40'
        ],
        [
            'title' => 'Đánh giá Shop XYZ',
            'body' => 'Dịch vụ tốt, nhân viên nhiệt tình...',
            'rating' => 4,
            'reviewer' => 'Người dùng 2',
            'reviewer_image' => 'https://via.placeholder.com/40'
        ],
        // Add more sample reviews
        [
            'title' => 'Review Điện Thoại Cũ',
            'body' => 'Mua được một chiếc iPhone cũ còn rất mới...',
            'rating' => 5,
            'reviewer' => 'Người dùng 3',
            'reviewer_image' => 'https://via.placeholder.com/40'
        ],
        [
            'title' => 'Đánh giá Laptop Second Hand',
            'body' => 'Cửa hàng có nhiều mẫu laptop cũ chất lượng...',
            'rating' => 4,
            'reviewer' => 'Người dùng 4',
            'reviewer_image' => 'https://via.placeholder.com/40'
        ],
        [
            'title' => 'Review Máy Ảnh Cũ',
            'body' => 'Tìm được máy ảnh cũ giá tốt, hoạt động tốt...',
            'rating' => 5,
            'reviewer' => 'Người dùng 5',
            'reviewer_image' => 'https://via.placeholder.com/40'
        ],
        [
            'title' => 'Đánh giá Tai Nghe Second Hand',
            'body' => 'Mua được tai nghe cao cấp giá rẻ...',
            'rating' => 4,
            'reviewer' => 'Người dùng 6',
            'reviewer_image' => 'https://via.placeholder.com/40'
        ]
    ];

    foreach($reviews as $review): 
    ?>
        <div class="review-card">
            <div class="star-rating">
                <?php 
                for($i = 0; $i < 5; $i++) {
                    if($i < $review['rating']) {
                        echo '<i class="fas fa-star"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
                ?>
            </div>
            <h3 class="review-title"><?php echo $review['title']; ?></h3>
            <p class="review-body"><?php echo $review['body']; ?></p>
            <div class="reviewer-info">
                <img src="<?php echo $review['reviewer_image']; ?>" alt="reviewer">
                <span><?php echo $review['reviewer']; ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <a href="#">Previous</a>
    <a href="#" class="active">1</a>
    <a href="#">2</a>
    <a href="#">3</a>
    <a href="#">67</a>
    <a href="#">68</a>
    <a href="#">Next</a>
</div>

<?php
include 'includes/footer.php';
?>