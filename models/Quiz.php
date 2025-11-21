<?php
// models/Quiz.php - Model để quản lý quiz

require_once __DIR__ . '/../config/database.php';

class Quiz {
    private $db;
    private $table_saved_words = 'saved_words';
    private $table_local_words = 'local_words';
    private $table_quiz_results = 'quiz_results';
    private $table_quiz_result_details = 'quiz_result_details';

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = (new Database())->connect();
        }
    }

    /**
     * Lấy các từ ngẫu nhiên để tạo quiz
     * 
     * @param int $userId ID của user
     * @param int $limit Số lượng từ cần lấy (mặc định 10)
     * @return array Danh sách từ
     */
    public function getRandomWords($userId, $limit = 10) {
        $query = "
            SELECT 
                lw.id,
                lw.word,
                lw.ipa,
                lw.audio_link,
                lw.part_of_speech,
                lw.senses,
                lw.level
            FROM " . $this->table_saved_words . " sw
            JOIN " . $this->table_local_words . " lw ON sw.local_word_id = lw.id
            WHERE sw.user_id = :user_id
            ORDER BY RAND()
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả từ đã lưu của user
     * 
     * @param int $userId ID của user
     * @return array Danh sách từ
     */
    public function getAllSavedWords($userId) {
        $query = "
            SELECT 
                lw.id,
                lw.word,
                lw.ipa,
                lw.audio_link,
                lw.part_of_speech,
                lw.senses
            FROM " . $this->table_saved_words . " sw
            JOIN " . $this->table_local_words . " lw ON sw.local_word_id = lw.id
            WHERE sw.user_id = :user_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lưu kết quả quiz
     * 
     * @param int $userId ID của user
     * @param int $score Điểm số
     * @param int $totalQuestions Tổng số câu hỏi
     * @return int|false ID của quiz result hoặc false nếu thất bại
     */
    public function saveQuizResult($userId, $score, $totalQuestions) {
        // Nếu user chưa có quiz, tạo một default quiz
        $userQuizId = $this->getOrCreateDefaultQuiz($userId);
        
        if (!$userQuizId) {
            return false;
        }

        $query = "
            INSERT INTO " . $this->table_quiz_results . " (user_id, user_quiz_id, score, total_questions)
            VALUES (:user_id, :user_quiz_id, :score, :total_questions)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_quiz_id', $userQuizId, PDO::PARAM_INT);
        $stmt->bindParam(':score', $score, PDO::PARAM_INT);
        $stmt->bindParam(':total_questions', $totalQuestions, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Lấy hoặc tạo default quiz cho user
     */
    private function getOrCreateDefaultQuiz($userId) {
        // Kiểm tra xem user có quiz "Default" chưa
        $stmt = $this->db->prepare("SELECT id FROM user_quizzes WHERE user_id = ? AND name = 'Default' LIMIT 1");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['id'];
        }

        // Tạo default quiz nếu chưa có
        $stmt = $this->db->prepare("INSERT INTO user_quizzes (user_id, name, description) VALUES (?, 'Default', 'Quiz mặc định')");
        if ($stmt->execute([$userId])) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Lưu chi tiết câu trả lời
     * 
     * @param int $quizResultId ID của quiz result
     * @param int $wordId ID của từ
     * @param string $userAnswer Câu trả lời của user
     * @param string $correctAnswer Câu trả lời đúng
     * @param bool $isCorrect Có đúng không
     * @return bool
     */
    public function saveQuestionDetail($quizResultId, $wordId, $userAnswer, $correctAnswer, $isCorrect) {
        $query = "
            INSERT INTO " . $this->table_quiz_result_details . " 
            (quiz_result_id, local_word_id, user_answer, correct_answer, is_correct)
            VALUES (:quiz_result_id, :word_id, :user_answer, :correct_answer, :is_correct)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quiz_result_id', $quizResultId, PDO::PARAM_INT);
        $stmt->bindParam(':word_id', $wordId, PDO::PARAM_INT);
        $stmt->bindParam(':user_answer', $userAnswer, PDO::PARAM_STR);
        $stmt->bindParam(':correct_answer', $correctAnswer, PDO::PARAM_STR);
        $stmt->bindParam(':is_correct', $isCorrect, PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    /**
     * Lấy kết quả quiz của user
     * 
     * @param int $userId ID của user
     * @param int $limit Số lượng kết quả
     * @param int $offset Vị trí bắt đầu
     * @return array Danh sách kết quả quiz
     */
    public function getQuizResults($userId, $limit = 10, $offset = 0) {
        $query = "
            SELECT *
            FROM " . $this->table_quiz_results . "
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết kết quả quiz
     * 
     * @param int $quizResultId ID của quiz result
     * @return array Danh sách chi tiết
     */
    public function getQuizResultDetails($quizResultId) {
        $query = "
            SELECT 
                qrd.id,
                qrd.local_word_id,
                qrd.user_answer,
                qrd.correct_answer,
                qrd.is_correct,
                lw.word,
                lw.ipa,
                lw.audio_link,
                lw.part_of_speech
            FROM " . $this->table_quiz_result_details . " qrd
            JOIN " . $this->table_local_words . " lw ON qrd.local_word_id = lw.id
            WHERE qrd.quiz_result_id = :quiz_result_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quiz_result_id', $quizResultId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết một kết quả quiz
     * 
     * @param int $quizResultId ID của quiz result
     * @return array|null Chi tiết quiz hoặc null
     */
    public function getQuizResultDetail($quizResultId) {
        $query = "
            SELECT *
            FROM " . $this->table_quiz_results . "
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $quizResultId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra user có từ lưu không
     * 
     * @param int $userId ID của user
     * @return bool
     */
    public function hasSavedWords($userId) {
        $query = "
            SELECT COUNT(*) as total
            FROM " . $this->table_saved_words . "
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Đếm số từ đã lưu
     * 
     * @param int $userId ID của user
     * @return int Số lượng từ
     */
    public function countSavedWords($userId) {
        $query = "
            SELECT COUNT(*) as total
            FROM " . $this->table_saved_words . "
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
