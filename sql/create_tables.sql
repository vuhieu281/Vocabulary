-- ==========================================
-- 1) TẠO DATABASE
-- ==========================================
CREATE DATABASE IF NOT EXISTS vocabulary_db;
USE vocabulary_db;

-- ==========================================
-- 1) LOCAL WORDS ─ (BẢNG TRUNG TÂM)
-- ==========================================
CREATE TABLE local_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(100) NOT NULL UNIQUE, -- Thêm UNIQUE để đảm bảo từ không trùng
    part_of_speech VARCHAR(50),
    ipa VARCHAR(100),
    audio_link VARCHAR(255),
    senses TEXT, -- (Lưu nghĩa, ví dụ... có thể là JSON hoặc text)
    level VARCHAR(10),
    oxford_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 2) USERS ─ (Không thay đổi)
-- ==========================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
<<<<<<< HEAD
-- ==========================================
-- 3) TOPICS
-- ==========================================
CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 4) TOPIC WORDS ─ (ĐÃ ĐIỀU CHỈNH)
-- Liên kết chủ đề và từ vựng
-- ==========================================
CREATE TABLE topic_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    local_word_id INT NOT NULL, -- <-- THAY ĐỔI: Dùng ID
    
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE
);

-- ==========================================
-- 5) SAVED WORDS ─ (ĐÃ ĐIỀU CHỈNH)
-- Từ vựng user đã lưu
-- ==========================================
CREATE TABLE saved_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    local_word_id INT NOT NULL, -- <-- THAY ĐỔI: Dùng ID
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE,
    
    -- Đảm bảo 1 user không lưu 1 từ 2 lần
    UNIQUE(user_id, local_word_id) 
);

-- ==========================================
-- 6) SEARCH HISTORY ─ (ĐÃ ĐIỀU CHỈNH)
-- Lịch sử tra cứu của user
-- ==========================================
CREATE TABLE search_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    local_word_id INT NOT NULL, -- <-- THAY ĐỔI: Dùng ID
    searched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE
);

-- ==========================================
-- 7) QUIZ RESULTS ─ (Không thay đổi)
-- Lưu điểm tổng quát của bài quiz
-- ==========================================
CREATE TABLE quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
<<<<<<< HEAD
    user_quiz_id INT,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user_quiz_id) REFERENCES user_quizzes(id) ON DELETE CASCADE
=======
    score INT NOT NULL,
    total_questions INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
>>>>>>> dd20d1fd78b2ea20cb13823a93939e86f972f189
);

-- ==========================================
-- 8) QUIZ RESULT DETAILS ─ (ĐÃ ĐIỀU CHỈNH)
-- Lưu chi tiết các câu đã trả lời
-- ==========================================
CREATE TABLE quiz_result_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_result_id INT NOT NULL,
    local_word_id INT NOT NULL, -- <-- THAY ĐỔI: Dùng ID (biết được câu hỏi là từ nào)
    user_answer TEXT NOT NULL,
    correct_answer TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT 0,
    
    FOREIGN KEY (quiz_result_id) REFERENCES quiz_results(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE
);

-- ==========================================
-- 9) USER QUIZZES (ĐỊNH NGHĨA BỘ QUIZ)
-- Lưu trữ các bộ quiz do user tự tạo
-- ==========================================
CREATE TABLE user_quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,               -- User đã tạo bộ quiz này
    name VARCHAR(150) NOT NULL,         -- Tên bộ quiz (VD: "Quiz động vật", "30 từ khó")
    description TEXT DEFAULT NULL,      -- Mô tả thêm (nếu cần)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ==========================================
-- 10) USER QUIZ WORDS (TỪ VỰNG TRONG BỘ QUIZ)
-- Liên kết bộ quiz (bảng 9) với từ vựng (bảng 1)
-- ==========================================
CREATE TABLE user_quiz_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_quiz_id INT NOT NULL,          -- Thuộc bộ quiz nào (ID từ bảng 9)
    local_word_id INT NOT NULL,         -- Là từ vựng nào (ID từ bảng 1)
    
    FOREIGN KEY (user_quiz_id) REFERENCES user_quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (local_word_id) REFERENCES local_words(id) ON DELETE CASCADE,
    
    -- Đảm bảo 1 từ không bị thêm 2 lần vào 1 bộ quiz
    UNIQUE(user_quiz_id, local_word_id)
);