# 🌱 Hướng dẫn Seed Topics

Tài liệu này hướng dẫn cách tạo 3 chủ đề mẫu với 10 từ mỗi chủ đề.

## 📋 Các bước

### 1️⃣ Chắc chắn dữ liệu đã được import

Trước tiên, hãy import từ vựng từ Oxford Dictionary:

```bash
# Truy cập: /Vocabulary/sql/import_oxford.php
http://localhost/Vocabulary/sql/import_oxford.php
```

✅ Nên có ít nhất 100+ từ trong bảng `local_words`

### 2️⃣ Chạy Seed Topics Script

Truy cập URL sau để tạo 3 chủ đề mẫu:

```
http://localhost/Vocabulary/sql/seed_topics.php
```

**Kết quả sẽ hiển thị:**
- ✅ 3 chủ đề được tạo
- 📖 30 từ được liên kết (10 từ x 3 chủ đề)
- Đường link để xem trang Topics

### 3️⃣ Kiểm tra kết quả

Truy cập trang Topics để xem các chủ đề:

```
http://localhost/Vocabulary/public/index.php?route=topics
```

---

## 📊 3 Chủ đề Mẫu

### 1. Animals (Động vật)
- dog, cat, elephant, lion, tiger, bear, monkey, bird, fish, horse

### 2. Food (Thực phẩm)
- apple, bread, cheese, milk, egg, rice, chicken, beef, pizza, soup

### 3. Travel (Du lịch)
- hotel, airport, ticket, passport, luggage, map, tour, beach, mountain, flight

---

## 🔧 Troubleshooting

### ⚠️ Lỗi: "Từ không tìm thấy"
- Đảm bảo đã import từ vựng từ `sql/import_oxford.php` trước
- Các từ phải khớp chính xác (case-sensitive)

### ⚠️ Không thể kết nối database
- Kiểm tra `config/database.php` 
- Đảm bảo MySQL server đang chạy
- Kiểm tra credentials database

### ⚠️ Chủ đề không hiển thị
- Làm mới trình duyệt (Ctrl+F5)
- Kiểm tra trong `topics` table có dữ liệu không
- Kiểm tra trong `topic_words` table có liên kết không

---

## 📝 Ghi chú

- Script có hỗ trợ chạy nhiều lần mà không tạo duplicate
- Nếu chủ đề đã tồn tại, script sẽ skip
- Có thể chỉnh sửa `seed_topics.php` để thêm/xóa chủ đề

---

**Tạo ngày:** November 19, 2025
