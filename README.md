#  Vocabulary Web

>  Nền tảng học từ vựng tiếng Anh toàn diện với Flashcard, Quiz & Chatbot AI

Vocabulary Web là ứng dụng web giúp người dùng nâng cao vốn từ vựng tiếng Anh một cách chủ động và hiệu quả. Người dùng có thể tra cứu từ, lưu yêu thích, ôn tập qua Flashcard/Quiz, và tương tác với Chatbot AI.

---

##  Mục Lục

- [Giới thiệu](#-giới-thiệu)
- [Tính năng](#-tính-năng)
- [Thành viên & Phân công](#-thành-viên--phân-công)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Cài đặt & Chạy](#-cài-đặt--chạy)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Sử dụng](#-sử-dụng)
- [Troubleshooting](#-troubleshooting)

---

## Giới thiệu

**Vocabulary Web** là dự án website dành cho người muốn học từ vựng tiếng Anh một cách hiệu quả.

**Điểm nổi bật:**
-  Kho từ vựng 6000+ từ từ Oxford English Dictionary
-  Hệ thống Flashcard tương tác
-  Quiz đa dạng (trắc nghiệm)
-  Chatbot AI hỗ trợ trực tuyến
-  Học theo chủ đề 
- Lưu từ yêu thích & theo dõi lịch sử
- Admin dashboard quản lý hệ thống

---

##  Tính năng

### **Người dùng bình thường**

| Tính năng | Mô tả |
|:---|:---|
|  **Tài khoản** | Đăng ký, đăng nhập, quản lý profile |
|  **Tra cứu từ** | Tìm kiếm từ, xem chi tiết (định nghĩa, IPA, ví dụ) |
|  **Lưu từ** | Lưu từ yêu thích vào danh sách riêng |
|  **Học theo chủ đề** | 20+ chủ đề từ vựng (Food, Animals, Business...) |
|  **Flashcard** | 2 mode: Learn & Review |
|  **Quiz** | Trắc nghiệm + Điền từ, xem kết quả chi tiết |
|  **Chatbot** | Hỏi AI về từ, cách dùng, ví dụ |
|  **Lịch sử** | Xem & xóa lịch sử tra cứu |

###  **Admin**

| Tính năng | Mô tả |
|:---|:---|
|  **Dashboard** | Thống kê người dùng, từ, chủ đề |
|  **Quản lý user** | CRUD người dùng |
|  **Quản lý chủ đề** | Thêm/sửa/xóa topics, upload ảnh |
|  **Quản lý từ** | Thêm/sửa/xóa từ, phân loại chủ đề |

---

##  Thành viên & Phân công

| Tên | Vai trò | Công việc |
|:---|:---|:---|
| **Minh Hiếu** | Frontend & Backend | Database, Layout chung, Home, Word Details, Save & History |
| **Vũ Hiếu** | Frontend & Backend | Database, Layout chung, Hỗ trợ xử lý logic, CSS responsive |
| **Minh Đức** | Frontend & Backend | Flashcard, Quiz, Topics Learning |
| **Việt Dũng** | Frontend & Backend & AI | Auth (Login/Register), Profile, Chatbot AI integration |
| **Tuấn Đạt** | Admin & Fullstack | Admin Dashboard, CRUD Admin, Upload ảnh |

---

##  Công nghệ sử dụng

### Frontend
- HTML5, CSS3, JavaScript (Vanilla)

### Backend  
- PHP 7.4+ (Vanilla, không framework)
- PDO (Database abstraction)
- Session (Authentication)
- Bcrypt (Password hashing)

### Database
- MySQL 5.7+ / MariaDB
- UTF-8mb4 charset
- Foreign keys

### Server & Tools
- Apache 2.4+ (XAMPP/WAMP)
- Git, Visual Studio Code
- phpMyAdmin

### AI
- OpenAI API hoặc Custom Chatbot

---

##  Cài đặt & Chạy

###  Yêu cầu

- XAMPP 7.4+ (hoặc WAMP)
- PHP 7.4+ với PDO_MySQL extension
- MySQL 5.7+
- Git
- Web browser hiện đại

### 🔧 Hướng dẫn cài đặt

#### **Bước 1: Clone dự án**

```powershell
cd C:\xampp\htdocs
git clone https://github.com/username/vocabulary-web.git Vocabulary
cd Vocabulary
```

#### **Bước 2: Tạo Database**

**Cách A: Sử dụng phpMyAdmin**
1. Mở http://localhost/phpmyadmin
2. Tạo database: `vocabulary_db` (Charset: utf8mb4_unicode_ci)
3. Import `sql/create_tables.sql`

**Cách B: MySQL Command Line**
```powershell
cd C:\xampp\mysql\bin
mysql -u root

# Trong MySQL prompt:
> CREATE DATABASE vocabulary_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> USE vocabulary_db;
> SOURCE C:\xampp\htdocs\Vocabulary\sql\create_tables.sql;
> EXIT;
```

#### **Bước 3: Import dữ liệu từ vựng (tuỳ chọn)**

```powershell
cd C:\xampp\htdocs\Vocabulary
php sql/import_oxford.php
```

#### **Bước 4: Khởi động & Chạy**

1. Mở XAMPP Control Panel
2. Start **Apache** & **MySQL**
3. Truy cập: **http://localhost/Vocabulary/public**

###  Tài khoản mặc định

```
Email:    admin@vocabulary.local
Password: admin123
```

 **Hãy đổi mật khẩu sau khi đăng nhập lần đầu!**

---

##  Cấu trúc dự án

```
Vocabulary/
├── api/                           # API endpoints cho AJAX requests
│   ├── search_words.php              # Tìm kiếm từ với autocomplete
│   ├── save_word.php                 # Lưu từ yêu thích vào saved_words
│   ├── check_saved_word.php          # Kiểm tra từ đã lưu chưa
│   ├── get_search_history.php        # Lấy lịch sử tìm kiếm
│   ├── save_search_history.php       # Lưu từ tra cứu vào lịch sử
│   ├── delete_search_history.php     # Xóa mục lịch sử cụ thể
│   ├── clear_all_search_history.php  # Xóa toàn bộ lịch sử
│   ├── chatbot.php                   # Giao diện gọi API Chatbot AI
│   ├── word-detail.php               # Lấy chi tiết từ (JSON)
│   ├── test_quiz_submit.php          # Submit bài quiz và lưu kết quả
│   ├── verify_topic_words.php        # Lấy danh sách từ của topic
│   ├── remove_word_from_topic.php    # Xóa từ khỏi topic (admin)
│   └── admin_*.php                   # Admin APIs (add/edit/delete users, topics, words)
│
├── config/                        # File cấu hình chung
│   ├── database.php                  # Kết nối MySQL (PDO)
│   ├── topics.php                    # Cấu hình danh sách topics
│   └── chatbot.php                   # API key & URL Chatbot
│
├── controllers/                   # Logic xử lý chính (tầng Controller)
│   ├── AuthController.php            # Xử lý: Đăng ký, Đăng nhập, Profile, Đổi mật khẩu
│   ├── TopicController.php           # Xử lý: Danh sách topics, Chi tiết topic
│   ├── FlashcardController.php       # Xử lý: Learn mode, Review mode, Lật thẻ
│   ├── QuizController.php            # Xử lý: Tạo quiz, Làm quiz, Xem kết quả
│   ├── ChatbotController.php         # Xử lý: Gọi API Chatbot, Lưu lịch sử chat
│   └── AdminController.php           # Xử lý: Dashboard, CRUD Users/Topics/Words
│
├── models/                        # Database models (tầng Model - tương tác DB)
│   ├── User.php                      # Methods: getAll, getById, create, update, delete
│   ├── Word.php                      # Methods: getAll, getById, search, getByTopic
│   ├── Topic.php                     # Methods: getAll, getById, getWords, countWords
│   ├── Flashcard.php                 # Methods: getFlashcardsByUser, updateProgress
│   ├── Quiz.php                      # Methods: createQuiz, getQuestions, saveResult
│   ├── SearchHistory.php             # Methods: add, getByUser, delete, clearAll
│   └── ChatModel.php                 # Methods: saveChatHistory, getChatHistory
│
├── public/                        # Thư mục public (entry point - DocumentRoot)
│   ├── index.php                     # Router chính (xử lý GET route parameter)
│   ├── auth.php                      # Routes cho auth (login, register, logout)
│   ├── search.php                    # Routes cho tìm kiếm từ
│   ├── word.php                      # Routes cho chi tiết từ
│   └── css/                       # CSS stylesheets
│       ├── home.css                  # Style trang home, login, register
│       ├── search.css                # Style trang tìm kiếm
│       ├── admin.css                 # Style admin panel
│       ├── flashcard.css             # Style flashcard
│       ├── quiz.css                  # Style quiz
│       └── word-detail.css           # Style chi tiết từ
│
├── views/                         # HTML template files (tầng View)
│   ├── header.php                    # Navigation bar (chung tất cả trang)
│   ├── footer.php                    # Footer (chung tất cả trang)
│   ├── word-detail.php               # Template chi tiết từ (định nghĩa, IPA, ví dụ)
│   ├── flashcard-learn.php           # Template flashcard learn mode
│   │
│   ├── auth/                      # Templates xác thực
│   │   ├── login.php                 # Form đăng nhập
│   │   ├── register.php              # Form đăng ký
│   │   └── profile.php               # Trang profile, sửa thông tin, đổi mật khẩu
│   │
│   ├── home/                      # Templates trang chủ
│   │   └── home.php                  # Trang chủ (sau khi login)
│   │
│   ├── quiz/                      # Templates Quiz
│   │   ├── index.php                 # Danh sách quiz của user
│   │   ├── quiz.php                  # Giao diện làm quiz
│   │   └── result.php                # Kết quả quiz (điểm, chi tiết)
│   │
│   ├── topics/                    # Templates Topics
│   │   ├── index.php                 # Danh sách tất cả topics
│   │   └── detail.php                # Chi tiết topic (danh sách từ)
│   │
│   ├── chat/                      # Templates Chatbot
│   │   ├── index.php                 # Trang chat riêng (nếu có)
│   │   └── widget.php                # Widget chatbot (nhúng vào tất cả trang)
│   │
│   └── admin/                     # Templates Admin Panel
│       ├── _layout.php               # Layout admin chung
│       ├── _sidebar.php              # Sidebar menu admin
│       ├── admin-styles.php          # CSS admin inline
│       ├── dashboard.php             # Dashboard (thống kê, shortcut)
│       ├── users.php                 # Danh sách users, add, edit, delete
│       ├── edit-user.php             # Form sửa user
│       ├── topics.php                # Danh sách topics, add, edit, delete
│       ├── add-topic.php             # Form thêm topic
│       ├── edit-topic.php            # Form sửa topic
│       ├── words.php                 # Danh sách từ, search, filter
│       ├── add-word.php              # Form thêm từ
│       ├── edit-word.php             # Form sửa từ
│       ├── activities.php            # Lịch sử hoạt động hệ thống
│       └── user-activities.php       # Hoạt động của user cụ thể
│
├── sql/                           # Database scripts & data
│   ├── create_tables.sql             # SQL để tạo tất cả bảng (10+ tables)
│   ├── import_oxford.php             # Script PHP import dữ liệu từ CSV
│   ├── seed_topics.php               # Script seed dữ liệu topics mẫu
│   └── oxford_words.csv              # Dữ liệu ~6000 từ từ Oxford
│
├── uploads/                       # Thư mục lưu file upload từ user
│   └── topics/                    # Ảnh đại diện topics (uploaded)
│
├── logs/                          # Thư mục logs (error, access logs)
│
├── README.md                      # File này (hướng dẫn dự án)
├── .gitignore                     # Git ignore (uploads/, logs/, config/database.php)
└── .htaccess                      # Apache rewrite rules (URL friendly)
```

### Luồng dữ liệu (Data Flow)

```
User Request
    ↓
public/index.php (Router)
    ↓
controllers/XxxController.php (Logic xử lý)
    ↓
models/Xxx.php (Query database)
    ↓
config/database.php (PDO connection)
    ↓
MySQL Database
    ↓
models/Xxx.php (Return data)
    ↓
controllers/XxxController.php (Process data)
    ↓
views/xxx.php (Render HTML)
    ↓
Browser (Display)
```

###  Ví dụ: Luồng tra cứu từ

1. User gõ từ trong ô search (search.php)
2. JavaScript gọi AJAX → `/api/search_words.php?q=apple`
3. API nhận request, gọi `Word->search('apple')`
4. Model query: `SELECT * FROM local_words WHERE word LIKE '%apple%'`
5. Trả về JSON: `[{id: 1, word: "apple", ...}, ...]`
6. JavaScript render kết quả, hiển thị trong dropdown
7. User click từ → `word.php?id=1`
8. `WordController->detail(1)` → Query từ + topics + examples
9. Render `word-detail.php` với đầy đủ thông tin
10. User click "Save" → AJAX gọi `/api/save_word.php`
11. API thêm vào bảng `saved_words`

---

##  Sử dụng

###  **Người dùng**

**Tra cứu từ:**
```
http://localhost/Vocabulary/public/index.php?route=search
→ Nhập từ, xem chi tiết, save từ yêu thích
```

**Flashcard:**
```
http://localhost/Vocabulary/public/index.php?route=flashcard
→ Mode Learn: Xem tuần tự | Mode Review: Ôn tập
```

**Quiz:**
```
http://localhost/Vocabulary/public/index.php?route=quiz
→ Tạo quiz, làm bài, xem kết quả
```

**Chatbot:**
```
Widget trên mọi trang (góc phải dưới) → Hỏi AI
```

###  **Admin**

**Login Admin:**
```
Email: admin@vocabulary.local
Password: admin123
```

**Quản lý:**
```
http://localhost/Vocabulary/public/index.php?route=admin_dashboard
→ Users, Topics, Words management
```

---

## API Endpoints

### Tra cứu & Từ vựng
| Endpoint | Method | Mô tả |
|:---|:---:|:---|
| `/api/search_words.php?q=` | GET | Tìm kiếm từ |
| `/api/save_word.php` | POST | Lưu từ |
| `/api/check_saved_word.php?id=` | GET | Kiểm tra đã lưu |

### Admin
| Endpoint | Method | Mô tả |
|:---|:---:|:---|
| `/api/admin_add_user.php` | POST | Thêm user |
| `/api/admin_add_topic.php` | POST | Thêm topic |
| `/api/admin_add_word.php` | POST | Thêm từ |

---

## Database Schema

### Bảng chính

```
users (id, name, email, password, avatar, bio, role, created_at)
local_words (id, word, part_of_speech, ipa, audio_link, senses, level, oxford_url)
topics (id, name, description, image, color_hex, icon_name, created_at)
topic_words (id, topic_id, local_word_id)
saved_words (id, user_id, local_word_id, saved_at)
search_history (id, user_id, local_word_id, searched_at)
quiz_results (id, user_id, user_quiz_id, score, total_questions, created_at)
quiz_result_details (id, quiz_result_id, local_word_id, user_answer, correct_answer, is_correct)
chat_history (id, user_id, role, message, meta, created_at)
user_quizzes (id, user_id, name, description, created_at)
```

---

## Troubleshooting

###  "Connection refused" - MySQL không chạy
```powershell
# Mở XAMPP Control Panel, click Start MySQL
# Hoặc khởi động từ CLI:
cd C:\xampp\mysql\bin
mysqld
```

### "Access denied" - Password sai
```php
// Chỉnh sửa config/database.php
private $username = "root";
private $password = "";  // Để trống nếu chưa set
```

###  "Database doesn't exist"
```
Làm lại Bước 2: Tạo Database (phía trên)
```

###  Upload ảnh không hoạt động
```
1. Chuột phải vào uploads/topics/
2. Properties > Security > Edit > Permissions
3. Chọn tài khoản > Full Control > Apply
```

###  Chatbot không hoạt động
```php
// Cấu hình config/chatbot.php
define('CHATBOT_API_KEY', 'your_api_key_here');
define('CHATBOT_API_URL', 'https://api.example.com/chat');
```

---

##  Quy ước Code

```
PHP Classes:       PascalCase (UserController)
PHP Functions:     camelCase (getUserById)
Constants:         UPPER_SNAKE_CASE (DB_HOST)
JS Functions:      camelCase (loadWords)
HTML ID/Class:     kebab-case (user-form, btn-primary)
```

---

##  Bảo mật

 **Hiện tại:**
- Bcrypt password hashing
- Session-based authentication
- PDO prepared statements (prevent SQL injection)
- Input validation
- UTF-8 encoding

 **Cần cải thiện:**
- [ ] CSRF token validation
- [ ] Rate limiting
- [ ] HTTPS/SSL
- [ ] Password reset
- [ ] 2FA (Two-factor authentication)
- [ ] API key management

---

##  Tài liệu tham khảo

- [PHP Docs](https://www.php.net/docs.php)
- [MySQL Docs](https://dev.mysql.com/doc/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [OWASP Security](https://owasp.org/Top10/)

---

##  Liên hệ

Gặp vấn đề? 
-  Mở Issue trên GitHub
-  Tham gia Discussions
-  Email project

---

##  License

Dự án này được cung cấp cho mục đích giáo dục và học tập.

---

**Lần cập nhật cuối:** November 22, 2025

🎓 **Happy Learning!** 
