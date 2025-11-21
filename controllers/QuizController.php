<?php
// controllers/QuizController.php - Controller để xử lý quiz

require_once __DIR__ . '/../models/Quiz.php';

class QuizController {
    private $quiz;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->quiz = new Quiz();
    }

    /**
     * Hiển thị trang chính quiz
     */
    public function index() {
        if (!$this->isUserLoggedIn()) {
            header('Location: /Vocabulary/public/index.php?route=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $hasWords = $this->quiz->hasSavedWords($userId);
        $totalWords = $this->quiz->countSavedWords($userId);

        include_once __DIR__ . '/../views/header.php';
        include_once __DIR__ . '/../views/quiz/index.php';
        include_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Bắt đầu quiz mới
     */
    public function start() {
        if (!$this->isUserLoggedIn()) {
            header('Location: /Vocabulary/public/index.php?route=login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // Kiểm tra xem user có từ lưu không
        if (!$this->quiz->hasSavedWords($userId)) {
            header('Location: /Vocabulary/public/index.php?route=quiz');
            exit;
        }

        // Lấy 10 từ ngẫu nhiên
        $words = $this->quiz->getRandomWords($userId, 10);
        $allWords = $this->quiz->getAllSavedWords($userId);

        // Tạo quiz từ những từ này
        $quiz = $this->generateQuiz($words, $allWords);

        include_once __DIR__ . '/../views/header.php';
        include_once __DIR__ . '/../views/quiz/quiz.php';
        include_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Lưu kết quả quiz
     */
    public function submit() {
        // Start output buffering để tránh output trước JSON
        ob_start();
        
        // Set error log file
        $errorLogFile = __DIR__ . '/../logs/quiz_error.log';
        if (!is_dir(dirname($errorLogFile))) {
            mkdir(dirname($errorLogFile), 0755, true);
        }

        // Set header
        header('Content-Type: application/json');

        try {
            if (!$this->isUserLoggedIn()) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            // Lấy dữ liệu từ POST
            $answers = json_decode($_POST['answers'] ?? '{}', true);
            $quizData = json_decode($_POST['quiz'] ?? '[]', true);

            if (empty($answers) || empty($quizData)) {
                file_put_contents($errorLogFile, date('Y-m-d H:i:s') . " - Empty data: answers=" . count($answers) . ", quiz=" . count($quizData) . "\n", FILE_APPEND);
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
                exit;
            }

            $userId = $_SESSION['user_id'];
            $score = 0;
            $totalQuestions = count($quizData);

            // Tính điểm
            foreach ($quizData as $index => $question) {
                $userAnswer = $answers[$index] ?? null;
                $correctAnswer = $question['correct_answer'] ?? null;

                if ($userAnswer == $correctAnswer) {
                    $score++;
                }
            }

            // Lưu kết quả quiz
            $quizResultId = $this->quiz->saveQuizResult($userId, $score, $totalQuestions);

            if ($quizResultId) {
                // Lưu chi tiết câu trả lời
                foreach ($quizData as $index => $question) {
                    $userAnswer = $answers[$index] ?? null;
                    $correctAnswer = $question['correct_answer'] ?? null;
                    $isCorrect = ($userAnswer == $correctAnswer) ? 1 : 0;
                    
                    // word_id có thể null nên skip nếu không có
                    if (!isset($question['word_id']) || $question['word_id'] === null) {
                        continue;
                    }

                    $this->quiz->saveQuestionDetail(
                        $quizResultId,
                        $question['word_id'],
                        $userAnswer,
                        $correctAnswer,
                        $isCorrect
                    );
                }

                ob_end_clean();
                echo json_encode([
                    'success' => true,
                    'quiz_result_id' => $quizResultId,
                    'score' => $score,
                    'total' => $totalQuestions
                ]);
            } else {
                file_put_contents($errorLogFile, date('Y-m-d H:i:s') . " - Failed to save quiz result for user " . $userId . "\n", FILE_APPEND);
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Failed to save results']);
            }
        } catch (Exception $e) {
            file_put_contents($errorLogFile, date('Y-m-d H:i:s') . " - Exception: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }

        exit;
    }

    /**
     * Hiển thị kết quả quiz
     */
    public function result() {
        if (!$this->isUserLoggedIn()) {
            header('Location: /Vocabulary/public/index.php?route=login');
            exit;
        }

        $quizResultId = $_GET['id'] ?? 0;

        if (!$quizResultId) {
            header('Location: /Vocabulary/public/index.php?route=quiz');
            exit;
        }

        $quizResult = $this->quiz->getQuizResultDetail($quizResultId);
        $quizDetails = $this->quiz->getQuizResultDetails($quizResultId);

        // Debug log
        file_put_contents(__DIR__ . '/../logs/quiz_error.log', 
            date('Y-m-d H:i:s') . " - Result page: ID=$quizResultId, Found=" . ($quizResult ? 'YES' : 'NO') . "\n", 
            FILE_APPEND);

        if (!$quizResult) {
            header('Location: /Vocabulary/public/index.php?route=quiz');
            exit;
        }

        include_once __DIR__ . '/../views/header.php';
        include_once __DIR__ . '/../views/quiz/result.php';
        include_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Tạo dữ liệu quiz từ danh sách từ
     * 
     * @param array $words Danh sách từ chính
     * @param array $allWords Danh sách tất cả từ
     * @return array Quiz data
     */
    private function generateQuiz($words, $allWords) {
        $quizQuestions = [];
        $questionTypes = [
            'word_to_pos',      // Từ -> Loại từ
            'word_to_ipa',      // Từ -> IPA
            'ipa_to_word',      // IPA -> Từ
            'senses_to_word',   // Mô tả -> Từ
            'word_to_senses',   // Từ -> Mô tả
            'pos_to_word',      // Loại từ -> Từ
        ];

        $typeIndex = 0;
        foreach ($words as $word) {
            $questionType = $questionTypes[$typeIndex % count($questionTypes)];
            $question = $this->generateQuestion($word, $allWords, $questionType);
            if ($question) {
                $quizQuestions[] = $question;
                $typeIndex++;
            }
        }

        return $quizQuestions;
    }

    /**
     * Tạo một câu hỏi
     * 
     * @param array $word Từ chính
     * @param array $allWords Danh sách tất cả từ
     * @param string $questionType Loại câu hỏi
     * @return array|null Dữ liệu câu hỏi
     */
    private function generateQuestion($word, $allWords, $questionType) {
        $options = [];
        $correctAnswer = '';
        $question = '';

        switch ($questionType) {
            case 'word_to_pos':
                $question = "Từ \"{$word['word']}\" là loại từ gì?";
                $correctAnswer = $word['part_of_speech'] ?? 'Unknown';
                $options = $this->getRandomPOSList($word, $allWords, 4);
                break;

            case 'word_to_ipa':
                if (isset($word['ipa']) && $word['ipa'] && $word['ipa'] !== 'N/A') {
                    $question = "Phiên âm của từ \"{$word['word']}\" là gì?";
                    $correctAnswer = $word['ipa'];
                    $options = $this->getRandomIPA($word, $allWords, 4);
                } else {
                    return null; // Skip nếu không có IPA
                }
                break;

            case 'ipa_to_word':
                if (isset($word['ipa']) && $word['ipa'] && $word['ipa'] !== 'N/A') {
                    $question = "Phiên âm \"{$word['ipa']}\" là của từ nào?";
                    $correctAnswer = $word['word'];
                    $options = $this->getRandomWordsList($allWords, 4, $word['id']);
                } else {
                    return null; // Skip nếu không có IPA
                }
                break;

            case 'word_to_senses':
                $senseText = $this->extractFirstSense($word['senses'] ?? '');
                if ($senseText) {
                    $question = "Mô tả \"{$senseText}\" là của từ nào?";
                    $correctAnswer = $word['word'];
                    $options = $this->getRandomWordsList($allWords, 4, $word['id']);
                } else {
                    return null; // Skip nếu không có sense
                }
                break;

            case 'senses_to_word':
                $senseText = $this->extractFirstSense($word['senses'] ?? '');
                if ($senseText) {
                    $question = "Từ \"{$word['word']}\" có nghĩa là gì?";
                    $correctAnswer = $senseText;
                    $options = $this->getRandomSenses($word, $allWords, 4);
                } else {
                    return null; // Skip nếu không có sense
                }
                break;

            case 'pos_to_word':
                $pos = $word['part_of_speech'] ?? 'Unknown';
                if ($pos && $pos !== 'Unknown') {
                    $question = "Từ nào dưới đây là loại từ {$pos}?";
                    $correctAnswer = $word['word'];
                    $options = $this->getRandomByPOS($word, $allWords, 4);
                } else {
                    return null; // Skip nếu không có POS
                }
                break;
        }

        if (!$question || empty($options) || !$correctAnswer) {
            return null;
        }

        // Đảm bảo đáp án đúng có trong options
        if (!in_array($correctAnswer, $options)) {
            $options[0] = $correctAnswer;
        }

        // Shuffle options
        shuffle($options);

        return [
            'question' => $question,
            'type' => $questionType,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'word_id' => $word['id'],
            'audio_link' => $word['audio_link'] ?? ''
        ];
    }

    /**
     * Lấy các loại từ ngẫu nhiên - chỉ lấy POS khác nhau
     */
    private function getRandomPOSList($currentWord, $allWords, $count) {
        $currentPOS = $currentWord['part_of_speech'] ?? 'Unknown';

        // Lấy tất cả POS khác
        $otherPOS = [];
        foreach ($allWords as $word) {
            $wordPOS = $word['part_of_speech'] ?? 'Unknown';
            if ($wordPOS !== $currentPOS && !in_array($wordPOS, $otherPOS)) {
                $otherPOS[] = $wordPOS;
            }
        }

        // Build options
        $options = [$currentPOS];
        shuffle($otherPOS);
        
        for ($i = 0; $i < $count - 1 && $i < count($otherPOS); $i++) {
            $options[] = $otherPOS[$i];
        }

        // Nếu không đủ, thêm các loại từ khác (có thể trùng)
        // Để vẫn có 4 đáp án
        if (count($options) < $count && count($otherPOS) > 0) {
            shuffle($otherPOS);
            for ($i = count($options); $i < $count; $i++) {
                $options[] = $otherPOS[($i - 1) % count($otherPOS)];
            }
        }

        return array_slice($options, 0, $count);
    }

    /**
     * Lấy các IPA ngẫu nhiên - chỉ lấy từ khác
     */
    private function getRandomIPA($currentWord, $allWords, $count) {
        $ipas = [];
        $currentIPA = $currentWord['ipa'] ?? 'N/A';

        // Lấy các IPA khác
        $otherIPA = [];
        foreach ($allWords as $word) {
            $wordIPA = $word['ipa'] ?? 'N/A';
            if ($wordIPA !== $currentIPA && $wordIPA !== 'N/A' && !in_array($wordIPA, $otherIPA)) {
                $otherIPA[] = $wordIPA;
            }
        }

        // Shuffle và lấy count-1 IPA
        shuffle($otherIPA);
        for ($i = 0; $i < min($count - 1, count($otherIPA)); $i++) {
            $ipas[] = $otherIPA[$i];
        }

        // Thêm đáp án đúng
        $ipas[] = $currentIPA;

        return array_slice($ipas, 0, $count);
    }

    /**
     * Lấy danh sách từ ngẫu nhiên - trừ từ hiện tại
     */
    private function getRandomWordsList($allWords, $count, $excludeId = null) {
        $wordList = [];

        // Filter words (bỏ từ hiện tại)
        foreach ($allWords as $w) {
            if ($excludeId === null || $w['id'] != $excludeId) {
                $wordList[] = $w['word'];
            }
        }

        // Shuffle và lấy
        shuffle($wordList);
        return array_slice($wordList, 0, $count);
    }

    /**
     * Lấy danh sách mô tả ngẫu nhiên
     */
    private function getRandomSenses($currentWord, $allWords, $count) {
        $senses = [];
        
        // Lấy mô tả của từ hiện tại
        $currentSense = $this->extractFirstSense($currentWord['senses'] ?? '');
        if ($currentSense) {
            $senses[] = $currentSense;
        }

        // Lấy mô tả từ các từ khác
        $otherSenses = [];
        foreach ($allWords as $word) {
            if ($word['id'] != $currentWord['id']) {
                $sense = $this->extractFirstSense($word['senses'] ?? '');
                if ($sense && !in_array($sense, $otherSenses)) {
                    $otherSenses[] = $sense;
                }
            }
        }

        // Shuffle và thêm
        shuffle($otherSenses);
        for ($i = 0; $i < $count - 1 && $i < count($otherSenses); $i++) {
            $senses[] = $otherSenses[$i];
        }

        return array_slice($senses, 0, $count);
    }

    /**
     * Trích xuất mô tả đầu tiên từ senses JSON
     */
    private function extractFirstSense($sensesData) {
        if (empty($sensesData)) {
            return null;
        }

        try {
            // Cố gắng parse JSON
            $senseArray = json_decode($sensesData, true);
            if (is_array($senseArray) && !empty($senseArray)) {
                // Nếu là array, lấy phần tử đầu tiên
                $firstSense = $senseArray[0];
                if (is_array($firstSense) && isset($firstSense['definition'])) {
                    return $firstSense['definition']; // Không cắt
                } elseif (is_string($firstSense)) {
                    return $firstSense; // Không cắt
                }
            } elseif (is_string($senseArray)) {
                return $senseArray; // Không cắt
            }
        } catch (Exception $e) {
            // Nếu không phải JSON, xử lý như text thường
        }

        // Nếu là text thường, lấy phần đầu tiên
        $lines = explode('\n', $sensesData);
        if (!empty($lines[0])) {
            return trim($lines[0]); // Không cắt
        }

        return $sensesData; // Không cắt
    }

    /**
     * Lấy các từ theo loại từ - ưu tiên từ khác loại
     */
    private function getRandomByPOS($currentWord, $allWords, $count) {
        $currentPOS = $currentWord['part_of_speech'] ?? 'Unknown';

        // Lấy từ khác loại từ (ưu tiên)
        $diffPOS = [];
        foreach ($allWords as $word) {
            if ($word['id'] != $currentWord['id'] && ($word['part_of_speech'] ?? 'Unknown') !== $currentPOS) {
                $diffPOS[] = $word['word'];
            }
        }

        // Lấy từ cùng loại từ (backup)
        $samePOS = [];
        foreach ($allWords as $word) {
            if ($word['id'] != $currentWord['id'] && ($word['part_of_speech'] ?? 'Unknown') === $currentPOS) {
                $samePOS[] = $word['word'];
            }
        }

        // Build options
        $options = [$currentWord['word']];
        
        // Shuffle cả hai list
        shuffle($diffPOS);
        shuffle($samePOS);
        
        // Thêm từ khác loại (ưu tiên)
        $needCount = $count - 1; // Còn cần bao nhiêu từ
        $addedDiffPOS = 0;
        for ($i = 0; $i < count($diffPOS) && $addedDiffPOS < $needCount; $i++) {
            $options[] = $diffPOS[$i];
            $addedDiffPOS++;
        }
        
        // Nếu cần thêm, sử dụng từ cùng loại
        if (count($options) < $count) {
            for ($i = 0; $i < count($samePOS) && count($options) < $count; $i++) {
                $options[] = $samePOS[$i];
            }
        }
        
        // Nếu vẫn không đủ (hiếm khi xảy ra), lặp lại các từ đã có
        if (count($options) < $count) {
            $allAvailable = array_merge($diffPOS, $samePOS);
            shuffle($allAvailable);
            for ($i = 0; $i < count($allAvailable) && count($options) < $count; $i++) {
                $options[] = $allAvailable[$i];
            }
        }

        return array_slice($options, 0, $count);
    }

    /**
     * Kiểm tra user đã đăng nhập
     */
    private function isUserLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}
