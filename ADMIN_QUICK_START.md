# 🚀 Hướng Dẫn Sử Dụng Admin Panel

## ✅ Cài Đặt & Khởi Chạy

### 1️⃣ Chuẩn Bị Database

Đảm bảo database của bạn có các bảng cần thiết. Nếu chưa có, chạy file SQL:
```bash
mysql -u root -p vocabulary_db < sql/create_tables.sql
```

### 2️⃣ Tạo Tài Khoản Admin

Chạy lệnh SQL để tạo admin user (hoặc update existing user):
```sql
-- Tạo admin mới
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@example.com', '$2y$10$...hashed_password...', 'admin');

-- Hoặc update user hiện tại thành admin
UPDATE users SET role = 'admin' WHERE id = 1;
```

Để hash password, dùng code PHP:
```php
$password = password_hash('your_password_here', PASSWORD_BCRYPT);
echo $password;
```

### 3️⃣ Truy Cập Admin Panel

1. Mở trình duyệt
2. Đăng nhập với tài khoản admin
3. Truy cập: `http://localhost/Vocabulary/public/index.php?route=admin_dashboard`

---

## 📊 Các Tính Năng Chính

### 🏠 Dashboard
- Thống kê tổng quát (users, words, topics, searches)
- Biểu đồ tìm kiếm 7 ngày
- Top 10 từ được tìm kiếm
- Hoạt động gần đây

### 👥 Quản lý Người Dùng
**Danh sách**: `?route=admin_users`

**Chức năng:**
- ✏️ **Sửa**: Thay đổi tên, email, vai trò
- 👀 **Xem hoạt động**: Xem chi tiết tra cứu & lưu từ
- 🗑️ **Xóa**: Xóa người dùng

### 📚 Quản lý Từ Vựng
**Danh sách**: `?route=admin_words`

**Chức năng:**
- ➕ **Thêm từ**: `?route=admin_add_word`
- ✏️ **Sửa từ**: `?route=admin_edit_word&id={id}`
- 🗑️ **Xóa từ**: Xác nhận và xóa

**Thông tin từ vựng:**
- Từ tiếng Anh
- Loại từ (noun, verb, v.v.)
- IPA (phát âm)
- Nghĩa (meaning)
- Link âm thanh
- Level (A1-C2)
- Oxford URL

### 🏷️ Quản lý Chủ Đề
**Danh sách**: `?route=admin_topics`

**Chức năng:**
- ➕ **Thêm chủ đề**: `?route=admin_add_topic`
- ✏️ **Sửa chủ đề**: `?route=admin_edit_topic&id={id}`
- 🗑️ **Xóa chủ đề**: Xác nhận và xóa

**Thông tin chủ đề:**
- Tên chủ đề
- Mô tả
- Hình ảnh (JPG, PNG, GIF - tối đa 2MB)

### 📝 Lịch Sử Hoạt động
**Tổng quát**: `?route=admin_activities`

**Chi tiết người dùng**: `?route=admin_user_activities&user_id={id}`

**Thông tin hiển thị:**
- Biểu đồ tìm kiếm theo ngày
- Top 10 từ được tìm kiếm
- Hoạt động tìm kiếm & lưu từ
- Timestamp chi tiết

---

## 🎯 Quy Trình Thường Gặp

### ➕ Thêm Từ Vựng Mới
1. Vào: **Quản lý Từ vựng** → **+ Thêm từ vựng**
2. Điền thông tin:
   - Từ vựng * (bắt buộc)
   - Loại từ
   - IPA
   - Nghĩa
   - Link âm thanh
   - Level
3. Nhấn **💾 Lưu từ vựng**

### ➕ Thêm Chủ Đề Mới
1. Vào: **Quản lý Chủ đề** → **+ Thêm chủ đề**
2. Điền thông tin:
   - Tên chủ đề * (bắt buộc)
   - Mô tả
   - Hình ảnh (tuỳ chọn)
3. Nhấn **💾 Lưu chủ đề**

### 📊 Xem Hoạt động Người Dùng
1. Vào: **Quản lý User**
2. Tìm người dùng cần xem
3. Nhấn: **Xem hoạt động**
4. Xem danh sách tìm kiếm & lưu từ

---

## 📁 Cấu Trúc Thư Mục

```
Vocabulary/
├── controllers/
│   └── AdminController.php
├── models/
│   └── Admin.php
├── views/admin/
│   ├── dashboard.php
│   ├── users.php
│   ├── edit-user.php
│   ├── words.php
│   ├── add-word.php
│   ├── edit-word.php
│   ├── topics.php
│   ├── add-topic.php
│   ├── edit-topic.php
│   ├── activities.php
│   ├── user-activities.php
│   └── admin-styles.php
├── api/
│   ├── admin_add_word.php
│   ├── admin_edit_word.php
│   ├── admin_delete_word.php
│   ├── admin_add_topic.php
│   ├── admin_edit_topic.php
│   ├── admin_delete_topic.php
│   ├── admin_edit_user.php
│   └── admin_delete_user.php
├── uploads/topics/
│   └── [image files]
└── ADMIN_PANEL_README.md
```

---

## 🔐 Bảo Mật

✅ **Các biện pháp bảo mật:**
- Kiểm tra role admin trên tất cả trang
- Session-based authentication
- Xác nhận trước khi xóa (confirm dialog)
- Validate dữ liệu input
- Kiểm tra file upload (định dạng & kích thước)
- Hash password với BCrypt

---

## ⚠️ Lưu Ý Quan Trọng

1. **Admin duy nhất**: Tránh xóa admin user cuối cùng
2. **Backup dữ liệu**: Trước khi xóa hàng loạt
3. **Hình ảnh**: Lưu trong `uploads/topics/`, tối đa 2MB
4. **Phân trang**: Mặc định 15 item/trang (user), 20 item/trang (activities)

---

## 🆘 Xử Lý Lỗi Thường Gặp

### "Truy cập bị từ chối"
- Kiểm tra xem tài khoản có role = 'admin'
- Đăng nhập lại

### "Thêm/Sửa thất bại"
- Kiểm tra dữ liệu input
- Xem console log để tìm lỗi

### "Không thể upload hình ảnh"
- Kiểm tra định dạng (JPG, PNG, GIF)
- Kiểm tra kích thước (< 2MB)
- Kiểm tra quyền thư mục `uploads/`

### "Phân trang không hoạt động"
- Kiểm tra URL có parameter `page` hay không
- Xem giá trị `$page` trong code

---

## 📞 Hỗ Trợ

Nếu gặp vấn đề:
1. Kiểm tra console log (F12)
2. Xem database records
3. Kiểm tra permissions & file structure

---

## ✨ Tips & Tricks

💡 **Tìm kiếm nhanh**: Dùng Ctrl+F trong trang
💡 **Export data**: Có thể thêm feature này trong tương lai
💡 **Bulk operations**: Có thể thêm checkbox để xóa nhiều records
💡 **Search/Filter**: Có thể thêm search box cho các table

---

**Happy Admin!** 🎉
