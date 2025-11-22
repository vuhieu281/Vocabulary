-- ============================
-- TABLE: local_words
-- ============================
CREATE TABLE `local_words` (
  `id` int(11) NOT NULL,
  `word` varchar(100) NOT NULL,
  `part_of_speech` varchar(50) DEFAULT NULL,
  `ipa` varchar(100) DEFAULT NULL,
  `audio_link` varchar(255) DEFAULT NULL,
  `senses` text DEFAULT NULL,
  `level` varchar(10) DEFAULT NULL,
  `oxford_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `local_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `word` (`word`);

ALTER TABLE `local_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6176;

-- ============================
-- TABLE: quiz_results
-- ============================
CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_quiz_id` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_quiz_id` (`user_quiz_id`);

ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

-- ============================
-- TABLE: quiz_result_details
-- ============================
CREATE TABLE `quiz_result_details` (
  `id` int(11) NOT NULL,
  `quiz_result_id` int(11) NOT NULL,
  `local_word_id` int(11) NOT NULL,
  `user_answer` text NOT NULL,
  `correct_answer` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `quiz_result_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_result_id` (`quiz_result_id`),
  ADD KEY `local_word_id` (`local_word_id`);

ALTER TABLE `quiz_result_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

-- ============================
-- TABLE: saved_words
-- ============================
CREATE TABLE `saved_words` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `local_word_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `saved_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`local_word_id`),
  ADD KEY `local_word_id` (`local_word_id`);

ALTER TABLE `saved_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

-- ============================
-- TABLE: search_history
-- ============================
CREATE TABLE `search_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `local_word_id` int(11) NOT NULL,
  `searched_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `local_word_id` (`local_word_id`);

ALTER TABLE `search_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

-- ============================
-- TABLE: topics
-- ============================
CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT '#0d6efd',
  `icon_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

-- ============================
-- TABLE: topic_words
-- ============================
CREATE TABLE `topic_words` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `local_word_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `topic_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `local_word_id` (`local_word_id`);

ALTER TABLE `topic_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=819;

-- ============================
-- TABLE: users
-- ============================
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- ============================
-- TABLE: user_quizzes
-- ============================
CREATE TABLE `user_quizzes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `user_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `user_quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- ============================
-- TABLE: user_quiz_words
-- ============================
CREATE TABLE `user_quiz_words` (
  `id` int(11) NOT NULL,
  `user_quiz_id` int(11) NOT NULL,
  `local_word_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `user_quiz_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_quiz_id` (`user_quiz_id`,`local_word_id`),
  ADD KEY `local_word_id` (`local_word_id`);

ALTER TABLE `user_quiz_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- ============================
-- FOREIGN KEY CONSTRAINTS
-- ============================

ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE;

ALTER TABLE `quiz_result_details`
  ADD CONSTRAINT `quiz_result_details_ibfk_1` FOREIGN KEY (`quiz_result_id`) REFERENCES `quiz_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_result_details_ibfk_2` FOREIGN KEY (`local_word_id`) REFERENCES `local_words` (`id`) ON DELETE CASCADE;

ALTER TABLE `saved_words`
  ADD CONSTRAINT `saved_words_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_words_ibfk_2` FOREIGN KEY (`local_word_id`) REFERENCES `local_words` (`id`) ON DELETE CASCADE;

ALTER TABLE `search_history`
  ADD CONSTRAINT `search_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `search_history_ibfk_2` FOREIGN KEY (`local_word_id`) REFERENCES `local_words` (`id`) ON DELETE CASCADE;

ALTER TABLE `topic_words`
  ADD CONSTRAINT `topic_words_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_words_ibfk_2` FOREIGN KEY (`local_word_id`) REFERENCES `local_words` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_quizzes`
  ADD CONSTRAINT `user_quizzes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_quiz_words`
  ADD CONSTRAINT `user_quiz_words_ibfk_1` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quiz_words_ibfk_2` FOREIGN KEY (`local_word_id`) REFERENCES `local_words` (`id`) ON DELETE CASCADE;

CREATE TABLE IF NOT EXISTS `chat_history` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `role` ENUM('user','assistant') NOT NULL,
  `message` TEXT NOT NULL,
  `meta` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`user_id`),
  INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1. Tạo admin user mới
INSERT INTO users (name, email, password, role, created_at) 
VALUES (
    'Administrator',
    'admin@vocabulary.local',
    '$2y$10$YOixf7yyNVVVa7vw9i4Oue5h0H5gXQTH8s2L8J1K2M3N4O5P6Q7R8', -- password: 'admin123'
    'admin',
    NOW()
);

-- ============================================
-- MẬT KHẨU MẶC ĐỊNH:
-- Email: admin@vocabulary.local
-- Password: admin123
-- Role: admin
-- ============================================