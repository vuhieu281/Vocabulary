<?php

require_once __DIR__ . '/../models/Flashcard.php';
require_once __DIR__ . '/../models/User.php';

class FlashcardController {
    private $flashcard;
    private $user;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->flashcard = new Flashcard();
        $this->user = new User();
    }


    public function index() {
        // Kiểm tra user đã đăng nhập chưa
        if (!$this->isUserLoggedIn()) {
            header('Location: /Vocabulary/public/index.php?route=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Lấy số lượng từ đã lưu
        $totalWords = $this->flashcard->getSavedWordsCount($userId);
        
        // Kiểm tra xem user có từ lưu không
        $hasWords = $this->flashcard->hasSavedWords($userId);


        include_once __DIR__ . '/../views/header.php';
        
        // Include view flashcard
        include_once __DIR__ . '/../views/flashcard.php';
        
        // Include footer
        include_once __DIR__ . '/../views/footer.php';
    }

    public function learn() {
        // Kiểm tra user đã đăng nhập chưa
        if (!$this->isUserLoggedIn()) {
            header('Location: /Vocabulary/public/index.php?route=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Kiểm tra xem user có từ lưu không
        if (!$this->flashcard->hasSavedWords($userId)) {
            header('Location: /Vocabulary/public/index.php?route=flashcard');
            exit;
        }

        // Lấy tất cả flashcard
        $flashcards = $this->flashcard->getFlashcardsByUserId($userId);

        include_once __DIR__ . '/../views/header.php';
        
        include_once __DIR__ . '/../views/flashcard-learn.php';
        
        include_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Kiểm tra xem user đã đăng nhập chưa
     * 
     * @return bool True nếu đã đăng nhập
     */
    private function isUserLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}
