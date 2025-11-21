# 🔧 Hướng Dẫn Cài Đặt Admin Panel

## ✅ Yêu Cầu Hệ Thống

- PHP 7.4+
- MySQL 5.7+
- Apache với mod_rewrite (tuỳ chọn)
- Chart.js (CDN, tự động tải)

---

## 📦 Cài Đặt từng bước

### Bước 1: Đảm bảo Database Setup

Chạy SQL file để tạo các bảng cần thiết:

```bash
mysql -u root -p vocabulary_db < sql/create_tables.sql
```

### Bước 2: Tạo Admin User

**Option A: Dùng SQL Script**

```bash
mysql -u root -p vocabulary_db < sql/setup_admin.sql
```

**Option B: Insert thủ công**

```sql
INSERT INTO users (name, email, password, role) 
VALUES (
    'Administrator',
    'admin@vocabulary.local',
    '$2y$10$YOixf7yyNVVVa7vw9i4Oue5h0H5gXQTH8s2L8J1K2M3N4O5P6Q7R8',
    'admin'
);
```

**Option C: Update user hiện tại**

```sql
UPDATE users 
SET role = 'admin' 
WHERE id = 1;
```

### Bước 3: Tạo thư mục uploads

```bash
mkdir -p uploads/topics
chmod 755 uploads/topics
```

### Bước 4: Kiểm tra quyền file

```bash
chmod 755 views/admin
chmod 755 api
chmod 644 controllers/AdminController.php
chmod 644 models/Admin.php
```

### Bước 5: Khởi chạy Ứng dụng

```bash
# Nếu dùng PHP built-in server
php -S localhost:8000 -t public/

# Hoặc dùng Apache, truy cập:
http://localhost/Vocabulary/public/index.php
```

### Bước 6: Đăng Nhập

1. Truy cập trang login
2. Nhập thông tin:
   - Email: `admin@vocabulary.local`
   - Password: `admin123`

### Bước 7: Truy Cập Admin Panel

```
http://localhost/Vocabulary/public/index.php?route=admin_dashboard
```

---

## 📝 Thông Tin Admin Mặc Định

| Field | Value |
|-------|-------|
| **Email** | admin@vocabulary.local |
| **Password** | admin123 |
| **Role** | admin |

⚠️ **Lưu ý**: Đổi mật khẩu ngay sau khi cài đặt!

---

## 🔐 Sinh Password Hash

Nếu cần tạo password hash khác, chạy lệnh PHP:

```php
<?php
$password = 'your_password_here';
$hashed = password_hash($password, PASSWORD_BCRYPT);
echo $hashed;
?>
```

Sau đó dùng hash đó trong SQL:

```sql
UPDATE users 
SET password = '[hashed_password]' 
WHERE id = 1;
```

---

## ✅ Danh Sách Kiểm Tra

### Files Tạo Mới
- [x] models/Admin.php
- [x] controllers/AdminController.php
- [x] 12 view files trong views/admin/
- [x] 8 API endpoints trong api/

### Thay Đổi Hiện Tại
- [x] public/index.php - Thêm admin routes

### Documentation
- [x] ADMIN_PANEL_README.md
- [x] ADMIN_QUICK_START.md
- [x] ADMIN_CHANGES_SUMMARY.md
- [x] sql/setup_admin.sql
- [x] INSTALLATION_GUIDE.md (file này)

### Database
- [x] users table đã có role column
- [x] local_words table có đủ fields
- [x] topics table có image column
- [x] search_history table tồn tại
- [x] saved_words table tồn tại

### Thư Mục
- [x] views/admin/ được tạo
- [x] uploads/topics/ được tạo (hoặc tạo bằng tay)

---

## 🚀 Kiểm Tra Cài Đặt

### Test 1: Kiểm tra kết nối DB

```php
<?php
require_once 'config/database.php';
$db = new Database();
$conn = $db->connect();
echo "Database connection: " . ($conn ? "OK" : "FAILED");
?>
```

### Test 2: Kiểm tra Admin Access

1. Đăng nhập với admin account
2. Truy cập: `?route=admin_dashboard`
3. Nếu thấy dashboard → ✅ Success

### Test 3: Kiểm tra CRUD Operations

**Create:**
```
Vào: Quản lý Từ → Thêm từ → Lưu
```

**Read:**
```
Vào: Quản lý Từ → Xem danh sách
```

**Update:**
```
Vào: Quản lý Từ → Nhấn Sửa → Cập nhật
```

**Delete:**
```
Vào: Quản lý Từ → Nhấn Xóa → Xác nhận
```

### Test 4: Kiểm tra File Upload

1. Vào: Quản lý Chủ đề → Thêm chủ đề
2. Upload hình ảnh (< 2MB, JPG/PNG/GIF)
3. Kiểm tra file trong `uploads/topics/`

---

## 🐛 Xử Lý Lỗi Thường Gặp

### Lỗi 1: "Access Denied - Không phải Admin"

**Nguyên nhân**: User không có role = 'admin'

**Giải pháp**:
```sql
UPDATE users 
SET role = 'admin' 
WHERE id = 1;
```

### Lỗi 2: "Database connection failed"

**Nguyên nhân**: Kết nối MySQL thất bại

**Giải pháp**:
```bash
# Kiểm tra MySQL service
sudo service mysql start

# Kiểm tra credentials trong config/database.php
# Đảm bảo database 'vocabulary_db' tồn tại
```

### Lỗi 3: "Cannot upload image"

**Nguyên nhân**: Thư mục uploads không tồn tại hoặc không có quyền

**Giải pháp**:
```bash
mkdir -p uploads/topics
chmod 755 uploads
chmod 755 uploads/topics
```

### Lỗi 4: "Table not found"

**Nguyên nhân**: Chưa chạy SQL script

**Giải pháp**:
```bash
mysql -u root -p vocabulary_db < sql/create_tables.sql
```

### Lỗi 5: "Session not working"

**Nguyên nhân**: Session chưa start hoặc header issue

**Giải pháp**:
- Xác nhận `session_start()` được gọi
- Kiểm tra không có output trước `session_start()`

---

## 📊 Cấu Trúc Database

```sql
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Words table
CREATE TABLE local_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(100) UNIQUE,
    part_of_speech VARCHAR(50),
    ipa VARCHAR(100),
    audio_link VARCHAR(255),
    senses TEXT,
    level VARCHAR(10),
    oxford_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Topics table
CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Search history table
CREATE TABLE search_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    local_word_id INT,
    searched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE
);

-- Saved words table
CREATE TABLE saved_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    local_word_id INT,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, local_word_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE
);
```

---

## 🌐 URLs Admin Panel

| Trang | URL |
|-------|-----|
| Dashboard | `?route=admin_dashboard` |
| Quản lý User | `?route=admin_users` |
| Sửa User | `?route=admin_edit_user&id=1` |
| Quản lý Từ | `?route=admin_words` |
| Thêm Từ | `?route=admin_add_word` |
| Sửa Từ | `?route=admin_edit_word&id=1` |
| Quản lý Chủ đề | `?route=admin_topics` |
| Thêm Chủ đề | `?route=admin_add_topic` |
| Sửa Chủ đề | `?route=admin_edit_topic&id=1` |
| Lịch sử hoạt động | `?route=admin_activities` |
| Hoạt động User | `?route=admin_user_activities&user_id=1` |

---

## 📚 Tài Liệu Thêm

- 📖 [ADMIN_PANEL_README.md](./ADMIN_PANEL_README.md) - Tài liệu đầy đủ
- 🚀 [ADMIN_QUICK_START.md](./ADMIN_QUICK_START.md) - Hướng dẫn nhanh
- 📋 [ADMIN_CHANGES_SUMMARY.md](./ADMIN_CHANGES_SUMMARY.md) - Tóm tắt thay đổi
- 📝 [sql/setup_admin.sql](./sql/setup_admin.sql) - Script setup admin

---

## 💬 Hỗ Trợ

Nếu gặp vấn đề:

1. **Kiểm tra console log** (F12 Developer Tools)
2. **Xem database** bằng phpMyAdmin hoặc MySQL CLI
3. **Đọc error messages** trong PHP log
4. **Kiểm tra file permissions** của các folders

---

## ✨ Next Steps

Sau khi cài đặt thành công:

1. ✅ Đổi mật khẩu admin mặc định
2. ✅ Thêm dữ liệu test (từ vựng, chủ đề)
3. ✅ Tạo user test
4. ✅ Kiểm tra tất cả tính năng
5. ✅ Cấu hình backups

---

## 🎉 Installation Complete!

Admin panel đã sẵn sàng. Bắt đầu quản lý dữ liệu ngay!

**Version**: 1.0  
**Last Updated**: 2025-11-19
