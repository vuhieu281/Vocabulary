-- Tạo database
CREATE DATABASE bingxue;
USE bingxue;

-- Bảng users (người dùng)
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    role TINYINT DEFAULT 0, -- 0: user, 1: admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Bảng stores (cửa hàng)
CREATE TABLE stores (
    store_id INT PRIMARY KEY AUTO_INCREMENT,
    store_name VARCHAR(100) NOT NULL,
    address VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng reviews (đánh giá)
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    store_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id)
);

-- Bảng review_images (hình ảnh đánh giá)
CREATE TABLE review_images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(review_id)
);

-- Bảng comments (bình luận)
CREATE TABLE comments (
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT,
    user_id INT,
    content TEXT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(review_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


-- Dữ liệu mẫu cho bảng users
INSERT INTO users (username, email, password, phone, address, role) VALUES
('nguyenvana', 'vana@example.com', 'hashed_pw_1', '0901234567', '12 Nguyễn Trãi, Hà Nội', 0),
('lethib', 'lethib@example.com', 'hashed_pw_2', '0912345678', '45 Lý Thường Kiệt, TP.HCM', 0),
('phamcuong', 'cuongp@example.com', 'hashed_pw_3', '0988765432', '78 Hai Bà Trưng, Đà Nẵng', 0);

-- Dữ liệu mẫu cho bảng stores
INSERT INTO stores (store_name, address) VALUES
('Cửa hàng Điện tử A', '99 Trần Duy Hưng, Hà Nội'),
('Tech Store B', '100 Nguyễn Văn Linh, Đà Nẵng'),
('Điện máy C', '21 Nguyễn Huệ, TP.HCM');

-- Dữ liệu mẫu cho bảng reviews
INSERT INTO reviews (user_id, store_id, rating, content) VALUES
(1, 1, 5, 'Dịch vụ rất tốt, nhân viên thân thiện. Sẽ quay lại!'),
(2, 2, 4, 'Sản phẩm đa dạng, giá cả hợp lý, nhưng hơi đông khách.'),
(3, 1, 3, 'Giao hàng hơi chậm, nhưng sản phẩm ổn.'),
(1, 3, 4, 'Không gian đẹp, nhiều lựa chọn.');

-- Dữ liệu mẫu cho bảng review_images
INSERT INTO review_images (review_id, image_url) VALUES
(1, 'https://example.com/images/review1-1.jpg'),
(1, 'https://example.com/images/review1-2.jpg'),
(2, 'https://example.com/images/review2-1.jpg'),
(3, 'https://example.com/images/review3-1.jpg'),
(4, 'https://example.com/images/review4-1.jpg');

-- Dữ liệu mẫu cho bảng comments
INSERT INTO comments (review_id, user_id, content, rating) VALUES
(1, 2, 'Đồng ý, chỗ này phục vụ rất nhiệt tình.', 5),
(1, 3, 'Cũng từng mua ở đây, khá ổn.', 4),
(2, 1, 'Lần tới thử ghé mua xem sao.', 4),
(3, 2, 'Giao hàng mà chậm là mất điểm rồi.', 2),
(4, 3, 'Nhìn hình cũng muốn đi mua ngay.', 5);

INSERT INTO users (username, email, password, phone, role) VALUES
('admin', 'admin@techbook.com', '$2y$10$YourHashedPasswordHere', '0123456789', 1);
