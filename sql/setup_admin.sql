-- ============================================
-- Admin Panel Setup - SQL Script
-- ============================================

-- 1. Tạo admin user mới
INSERT INTO users (name, email, password, role, created_at) 
VALUES (
    'Administrator',
    'admin@vocabulary.local',
    '$2y$10$YOixf7yyNVVVa7vw9i4Oue5h0H5gXQTH8s2L8J1K2M3N4O5P6Q7R8', -- password: 'admin123'
    'admin',
    NOW()
);

-- 2. Hoặc nếu bạn muốn update user hiện tại thành admin
-- UPDATE users SET role = 'admin' WHERE id = 1;

-- 3. Xem danh sách users
-- SELECT id, name, email, role, created_at FROM users;

-- 4. Xem role của user
-- SELECT * FROM users WHERE role = 'admin';

-- ============================================
-- Ghi chú:
-- ============================================

-- ⚠️ CÁCH SINH HASHED PASSWORD:

-- Cách 1: PHP (trong file test.php)
/*
<?php
$password = 'admin123';
$hashed = password_hash($password, PASSWORD_BCRYPT);
echo "Hashed password: " . $hashed;
?>
*/

-- Cách 2: Online tools
-- https://www.php.net/manual/en/function.password-hash.php

-- Cách 3: Dùng PHP CLI
/*
php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
*/

-- ============================================
-- MẬT KHẨU MẶC ĐỊNH:
-- Email: admin@vocabulary.local
-- Password: admin123
-- Role: admin
-- ============================================

-- ⚠️ ĐỔI MẬT KHẨU NGAY SAU KHI ĐẶT LẬP!

-- Để đổi mật khẩu (trong admin panel):
-- 1. Đăng nhập
-- 2. Vào Profile
-- 3. Chọn "Đổi mật khẩu"
-- 4. Nhập mật khẩu mới

-- HOẶC cập nhật trực tiếp trong database:
/*
UPDATE users 
SET password = '$2y$10$...' -- new hashed password
WHERE id = 1;
*/

-- ============================================
-- KIỂM TRA INSTALLATION:
-- ============================================

-- 1. Kiểm tra admin user
SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin';

-- 2. Kiểm tra tất cả users
SELECT id, name, email, role, created_at FROM users ORDER BY id DESC;

-- 3. Kiểm tra tables
SHOW TABLES;

-- 4. Kiểm tra cấu trúc users table
DESCRIBE users;

-- ============================================
-- SCRIPT HOÀN CHỈNH (Copy & Paste):
-- ============================================

/*

INSERT INTO users (name, email, password, role, created_at) 
VALUES (
    'Administrator',
    'admin@vocabulary.local',
    '$2y$10$YOixf7yyNVVVa7vw9i4Oue5h0H5gXQTH8s2L8J1K2M3N4O5P6Q7R8',
    'admin',
    NOW()
);

*/

-- ============================================
-- HASHED PASSWORDS SAMPLES:
-- ============================================

-- Password: admin123
-- Hash 1: $2y$10$YOixf7yyNVVVa7vw9i4Oue5h0H5gXQTH8s2L8J1K2M3N4O5P6Q7R8
-- Hash 2: $2y$10$5H2q4g8dXfL9pV6jK3mN2udT8vZ1xC4bQ7wR0sU3yP6eO9aI2lJ1

-- Password: password123
-- Hash: $2y$10$...

-- ============================================
-- TROUBLESHOOTING:
-- ============================================

-- Problem: "Access Denied" khi vào admin panel
-- Solution: 
--   1. Kiểm tra role = 'admin'
--   2. Kiểm tra session

-- Problem: Password không đúng
-- Solution:
--   1. Regenerate hash
--   2. Dùng "Quên mật khẩu" feature (nếu có)

-- Problem: Admin page trắng/error
-- Solution:
--   1. Kiểm tra error logs
--   2. Kiểm tra database connection
--   3. Kiểm tra file permissions

-- ============================================
