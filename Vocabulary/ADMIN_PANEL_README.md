# Admin Panel Documentation

## 📋 Tổng Quan

Admin Panel là một hệ thống quản lý toàn diện cho ứng dụng Vocabulary. Nó cung cấp các tính năng:

- 📊 Dashboard với thống kê chi tiết
- 👥 Quản lý người dùng (Thêm, Sửa, Xóa)
- 📚 Quản lý từ vựng (Thêm, Sửa, Xóa)
- 🏷️ Quản lý chủ đề (Thêm, Sửa, Xóa)
- 📝 Lịch sử hoạt động của người dùng

## 🔐 Yêu Cầu Truy Cập

- Phải đăng nhập với tài khoản **admin**
- User role phải được set là `'admin'` trong cơ sở dữ liệu
- Tất cả các trang admin đều có bảo vệ quyền truy cập

## 📂 Cấu Trúc File

```
controllers/
├── AdminController.php          # Main controller cho admin panel
models/
├── Admin.php                    # Model xử lý logic admin
views/admin/
├── dashboard.php                # Trang dashboard
├── users.php                    # Danh sách người dùng
├── edit-user.php                # Form chỉnh sửa người dùng
├── words.php                    # Danh sách từ vựng
├── add-word.php                 # Form thêm từ vựng
├── edit-word.php                # Form chỉnh sửa từ vựng
├── topics.php                   # Danh sách chủ đề
├── add-topic.php                # Form thêm chủ đề
├── edit-topic.php               # Form chỉnh sửa chủ đề
├── activities.php               # Lịch sử hoạt động tổng quát
├── user-activities.php          # Lịch sử hoạt động của người dùng
└── admin-styles.php             # CSS chung cho admin
api/
├── admin_add_word.php           # API: Thêm từ vựng
├── admin_edit_word.php          # API: Chỉnh sửa từ vựng
├── admin_delete_word.php        # API: Xóa từ vựng
├── admin_add_topic.php          # API: Thêm chủ đề
├── admin_edit_topic.php         # API: Chỉnh sửa chủ đề
├── admin_delete_topic.php       # API: Xóa chủ đề
├── admin_edit_user.php          # API: Chỉnh sửa người dùng
└── admin_delete_user.php        # API: Xóa người dùng
```

## 🚀 Cách Sử Dụng

### 1. Truy cập Admin Panel
```
http://localhost/Vocabulary/public/index.php?route=admin_dashboard
```

### 2. Dashboard
- Xem thống kê tổng quát: Tổng số user, từ vựng, chủ đề, lượt tìm kiếm
- Biểu đồ tìm kiếm trong 7 ngày
- Top 10 từ được tìm kiếm nhiều nhất
- Hoạt động gần đây

### 3. Quản lý Người dùng
**URL**: `?route=admin_users`

**Các chức năng:**
- Xem danh sách tất cả người dùng (phân trang)
- **Sửa**: Thay đổi tên, email, vai trò (user/admin)
- **Xem hoạt động**: Xem lịch sử tra cứu và lưu từ của người dùng
- **Xóa**: Xóa người dùng (không thể tự xóa bản thân)

**URL chỉnh sửa**: `?route=admin_edit_user&id={user_id}`

### 4. Quản lý Từ Vựng
**URL**: `?route=admin_words`

**Các chức năng:**
- Xem danh sách từ vựng (phân trang)
- **Thêm**: `?route=admin_add_word`
  - Từ vựng *
  - Loại từ (noun, verb, adjective, v.v.)
  - IPA (phát âm)
  - Nghĩa (senses)
  - Link âm thanh
  - Level (A1-C2)
  - Oxford URL

- **Sửa**: `?route=admin_edit_word&id={word_id}`
- **Xóa**: Xóa từ vựng

### 5. Quản lý Chủ Đề
**URL**: `?route=admin_topics`

**Các chức năng:**
- Xem danh sách chủ đề (phân trang)
- **Thêm**: `?route=admin_add_topic`
  - Tên chủ đề *
  - Mô tả
  - Hình ảnh (JPG, PNG, GIF, tối đa 2MB)

- **Sửa**: `?route=admin_edit_topic&id={topic_id}`
- **Xóa**: Xóa chủ đề

Hình ảnh được lưu trong thư mục `uploads/topics/`

### 6. Lịch Sử Hoạt Động
**URL**: `?route=admin_activities`

**Các chức năng:**
- Xem hoạt động gần đây của tất cả người dùng
- Biểu đồ tìm kiếm trong 7 ngày
- Top 10 từ được tìm kiếm
- Lọc hoạt động theo loại (tìm kiếm / lưu từ)

**Hoạt động người dùng cụ thể**: `?route=admin_user_activities&user_id={user_id}`

## 🗄️ Cấu Trúc Dữ Liệu

### Tables được sử dụng:

#### users
```sql
- id (INT, PRIMARY KEY)
- name (VARCHAR)
- email (VARCHAR, UNIQUE)
- password (VARCHAR)
- role (ENUM: 'user', 'admin') -- DEFAULT: 'user'
- created_at (TIMESTAMP)
```

#### local_words
```sql
- id (INT, PRIMARY KEY)
- word (VARCHAR, UNIQUE)
- part_of_speech (VARCHAR)
- ipa (VARCHAR)
- audio_link (VARCHAR)
- senses (TEXT)
- level (VARCHAR)
- oxford_url (VARCHAR)
- created_at (TIMESTAMP)
```

#### topics
```sql
- id (INT, PRIMARY KEY)
- name (VARCHAR)
- description (TEXT)
- image (VARCHAR)
- created_at (TIMESTAMP)
```

#### search_history
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FK)
- local_word_id (INT, FK)
- searched_at (TIMESTAMP)
```

#### saved_words
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FK)
- local_word_id (INT, FK)
- saved_at (TIMESTAMP)
```

## 🔍 Admin Model Methods

### Dashboard Stats
```php
$admin->getDashboardStats()
// Returns: [
//   'total_users',
//   'total_words',
//   'total_topics',
//   'total_searches',
//   'new_users_7days',
//   'searches_7days'
// ]
```

### User Management
```php
$admin->getAllUsers($limit, $offset)
$admin->countUsers()
$admin->getUserById($id)
$admin->updateUser($id, $name, $email, $role)
$admin->deleteUser($id)
```

### Word Management
```php
$admin->getAllWords($limit, $offset)
$admin->countWords()
$admin->getWordById($id)
$admin->createWord($word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url)
$admin->updateWord($id, $word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url)
$admin->deleteWord($id)
```

### Topic Management
```php
$admin->getAllTopics($limit, $offset)
$admin->countTopics()
$admin->getTopicById($id)
$admin->createTopic($name, $description, $image)
$admin->updateTopic($id, $name, $description, $image)
$admin->deleteTopic($id)
```

### Activity Management
```php
$admin->getRecentActivities($limit)
$admin->getUserActivityHistory($user_id, $limit, $offset)
$admin->getActivityStats()
// Returns: [
//   'searches_by_date',
//   'top_searched_words'
// ]
```

## 🎨 Thiết Kế Giao Diện

- **Sidebar Navigation**: Menu cố định bên trái
- **Responsive Design**: Tích hợp CSS media queries
- **Color Scheme**: 
  - Primary: #3498db (Blue)
  - Secondary: #95a5a6 (Gray)
  - Danger: #e74c3c (Red)
  - Success: #2ecc71 (Green)
  - Dark bg: #2c3e50

## 📊 Thống Kê & Biểu Đồ

Dashboard sử dụng **Chart.js** để hiển thị:
- Biểu đồ đường (line chart) cho lượt tìm kiếm
- Top 10 từ với ranking

## 🔐 Bảo Mật

1. **Access Control**: Kiểm tra role admin trên mỗi trang
2. **Session-based**: Sử dụng session PHP
3. **Confirmation**: Xác nhận trước khi xóa
4. **File Upload Validation**: Kiểm tra định dạng & kích thước hình ảnh

## 📝 Quy Trình CRUD

### Thêm (Create)
1. Hiển thị form
2. User nhập dữ liệu
3. POST đến API endpoint
4. Validate & lưu vào DB
5. Redirect với message thành công

### Sửa (Update)
1. Load dữ liệu theo ID
2. Hiển thị form với giá trị hiện tại
3. User chỉnh sửa
4. POST đến API endpoint
5. Cập nhật DB
6. Redirect với message thành công

### Xóa (Delete)
1. Hiển thị danh sách
2. User click nút Xóa
3. Confirm dialog
4. POST đến API endpoint
5. Xóa từ DB
6. Redirect với message thành công

## 🚨 Error Handling

- Kiểm tra admin access trên mỗi trang
- Validate dữ liệu input
- Xử lý file upload errors
- Redirect với error messages

## 📱 Mobile Responsive

- Layout chuyển đổi từ flexbox sang flex-column
- Menu sidebar trở thành full-width
- Tables responsive

## 🔗 Liên Kết Nhanh

| Chức năng | URL |
|----------|-----|
| Dashboard | `?route=admin_dashboard` |
| Quản lý User | `?route=admin_users` |
| Quản lý Từ | `?route=admin_words` |
| Quản lý Chủ đề | `?route=admin_topics` |
| Lịch sử hoạt động | `?route=admin_activities` |

## 💡 Gợi Ý Mở Rộng

- [ ] Export data to Excel/PDF
- [ ] Advanced filtering & search
- [ ] Bulk operations
- [ ] User activity logs (login/logout)
- [ ] System settings management
- [ ] Backup database
- [ ] Email notifications
- [ ] Role-based permissions
