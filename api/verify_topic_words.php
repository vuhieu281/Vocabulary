<?php
// File test để verify flow: Admin tạo topic + thêm từ → User xem topic
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Admin.php';
require_once __DIR__ . '/models/Topic.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $admin = new Admin($db);
    $topicModel = new Topic($db);

    // Lấy topic ID từ query string (hoặc lấy topic cuối cùng)
    $topic_id = $_GET['id'] ?? null;
    
    if (!$topic_id) {
        // Lấy topic mới nhất
        $stmt = $db->query("SELECT id FROM topics ORDER BY id DESC LIMIT 1");
        $latest = $stmt->fetch(PDO::FETCH_ASSOC);
        $topic_id = $latest['id'] ?? null;
    }

    if (!$topic_id) {
        echo json_encode([
            'success' => false,
            'error' => 'Không có topic nào'
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Lấy thông tin topic
    $topic = $topicModel->getById($topic_id);
    $words = $topicModel->getWords($topic_id);
    
    // Lấy thông tin từ chi tiết từ local_words
    $wordIds = array_column($words, 'id');
    $wordDetails = [];
    if (!empty($wordIds)) {
        $placeholders = implode(',', $wordIds);
        $stmt = $db->query("SELECT id, word FROM local_words WHERE id IN ($placeholders)");
        $wordDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode([
        'success' => true,
        'topic' => $topic,
        'words_count' => count($words),
        'words' => $words,
        'debug' => [
            'query' => "SELECT lw.id, lw.word, lw.part_of_speech, lw.ipa, lw.audio_link, lw.senses FROM topic_words tw JOIN local_words lw ON tw.local_word_id = lw.id WHERE tw.topic_id = $topic_id ORDER BY lw.word ASC",
            'topic_words_table' => $db->query("SELECT * FROM topic_words WHERE topic_id = $topic_id")->fetchAll(PDO::FETCH_ASSOC)
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>
