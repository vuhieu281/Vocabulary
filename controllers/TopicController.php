<?php
require_once __DIR__ . '/../models/Topic.php';

class TopicController {
    public function index() {
        $topicModel = new Topic();
        $topics = $topicModel->getAll();

        // Preload counts
        foreach ($topics as &$t) {
            $t['count'] = $topicModel->countWords($t['id']);
        }

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/topics/index.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function detail($id) {
        $topicModel = new Topic();
        $topic = $topicModel->getById($id);
        if (!$topic) {
            header('Location: /Vocabulary/public/index.php?route=topics');
            exit;
        }

        $words = $topicModel->getWords($id);

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/topics/detail.php';
        include __DIR__ . '/../views/footer.php';
    }
}
