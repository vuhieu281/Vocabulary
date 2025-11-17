-- Tạo database
CREATE DATABASE techbook;
USE techbook;

-- Bảng Users (Người dùng)
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    phone VARCHAR(15),
    address TEXT,
    role INT DEFAULT 0, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng Stores (Cửa hàng)
CREATE TABLE stores (
    store_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng Reviews (Đánh giá)
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    store_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    content TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id)
);

-- Bảng Comments (Bình luận)
CREATE TABLE comments (
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT,
    user_id INT,
    content TEXT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(review_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Bảng Categories (Danh mục sản phẩm)
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Bảng store_categories (Liên kết cửa hàng-danh mục)
CREATE TABLE store_categories (
    store_id INT,
    category_id INT,
    PRIMARY KEY (store_id, category_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);




-- Dữ liệu mẫu cho Users
INSERT INTO users (username, email, password, avatar, phone, address)
VALUES 
('minhnguyen', 'minhnguyen@gmail.com', 'hashedpassword1', 'avatar1.png', '0912345678', 'Hà Nội, Việt Nam', 1),
('thanhtrang', 'trangthanh@gmail.com', 'hashedpassword2', 'avatar2.jpg', '0987654321', 'TP.HCM, Việt Nam', 0),
('quocanh', 'anhquoc@gmail.com', 'hashedpassword3', 'avatar3.jpg', '0938111222', 'Đà Nẵng, Việt Nam', 0),
('lehai', 'lehai@gmail.com', 'hashedpassword4', NULL, '0909777666', 'Cần Thơ, Việt Nam', 0);

-- Dữ liệu mẫu cho Stores
INSERT INTO stores (name, address)
VALUES 
('TechStore Hà Nội', '123 Trần Duy Hưng, Cầu Giấy, Hà Nội'),
('Điện Máy Sài Gòn', '456 Nguyễn Thị Minh Khai, Q.3, TP.HCM'),
('Laptop Đà Nẵng', '789 Lê Duẩn, Hải Châu, Đà Nẵng');

-- Dữ liệu mẫu cho Categories
INSERT INTO categories (name)
VALUES 
('Điện thoại'),
('Laptop'),
('Phụ kiện'),
('Âm thanh'),
('Thiết bị gia dụng');

-- Dữ liệu mẫu cho store_categories
INSERT INTO store_categories (store_id, category_id)
VALUES 
(1, 1), -- TechStore Hà Nội bán Điện thoại
(1, 2), -- TechStore Hà Nội bán Laptop
(1, 3), -- TechStore Hà Nội bán Phụ kiện
(2, 1), -- Điện Máy Sài Gòn bán Điện thoại
(2, 5), -- Điện Máy Sài Gòn bán Thiết bị gia dụng
(3, 2), -- Laptop Đà Nẵng bán Laptop
(3, 3); -- Laptop Đà Nẵng bán Phụ kiện

-- Dữ liệu mẫu cho Reviews
INSERT INTO reviews (user_id, store_id, rating, title, content, image)
VALUES 
(1, 1, 5, 'Dịch vụ tuyệt vời', 'Cửa hàng rất uy tín, nhân viên tư vấn nhiệt tình.', 'review1.jpg'),
(2, 1, 4, 'Giá tốt, nhiều khuyến mãi', 'Mua laptop được giảm giá, chất lượng ổn.', NULL),
(3, 2, 3, 'Chất lượng trung bình', 'Mình mua tai nghe, nghe ổn nhưng giao hàng hơi lâu.', 'review2.jpg'),
(1, 3, 5, 'Laptop chính hãng, bảo hành rõ ràng', 'Mình rất hài lòng với dịch vụ ở đây.', NULL),
(4, 2, 2, 'Chưa hài lòng', 'Điện thoại mua về bị lỗi màn hình, đổi trả hơi khó.', 'review3.png');

-- Dữ liệu mẫu cho Comments
INSERT INTO comments (review_id, user_id, content, rating)
VALUES 
(1, 2, 'Mình cũng thấy cửa hàng này làm việc rất chuyên nghiệp!', 5),
(1, 3, 'Cảm ơn bạn chia sẻ, mình sẽ ghé thử.', 4),
(3, 1, 'Mình đặt hàng lần trước cũng bị chậm.', 3),
(5, 3, 'Bạn thử liên hệ tổng đài xem sao.', 2),
(2, 4, 'Cửa hàng nhiều ưu đãi thật.', 4);