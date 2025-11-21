<?php
require_once __DIR__ . '/../models/ChatModel.php';

class ChatbotController {
    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        // Render chat view
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/chat/index.php';
        include __DIR__ . '/../views/footer.php';
    }
}
