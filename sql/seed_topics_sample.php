<?php
/**
 * Seed Topics với 3 chủ đề mẫu, mỗi chủ đề 10 từ
 * File: sql/seed_topics_sample.php
 */

require_once __DIR__ . '/../config/database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $db = (new Database())->connect();
    
    if (!$db) {
        die('❌ Không thể kết nối database');
    }

    // 3 chủ đề mẫu với 10 từ mỗi chủ đề
    $topicsData = [
        [
            'name' => 'Animals (Động vật)',
            'description' => 'Tìm hiểu các loài động vật từ quen thuộc đến hiếm gặp.',
            'words' => ['dog', 'cat', 'elephant', 'lion', 'tiger', 'bear', 'monkey', 'bird', 'fish', 'horse']
        ],
        [
            'name' => 'Food (Thực phẩm)',
            'description' => 'Các từ vựng liên quan đến thức ăn, đồ uống và nấu ăn.',
            'words' => ['apple', 'bread', 'cheese', 'milk', 'egg', 'rice', 'chicken', 'beef', 'pizza', 'soup']
        ],
        [
            'name' => 'Travel (Du lịch)',
            'description' => 'Từ vựng hữu ích khi đi du lịch và khám phá thế giới.',
            'words' => ['hotel', 'airport', 'ticket', 'passport', 'luggage', 'map', 'tour', 'beach', 'mountain', 'flight']
        ]
    ];

    // Chuẩn bị các statement
    $topicStmt = $db->prepare("INSERT INTO topics (name, description) VALUES (:name, :description)");
    $getWordStmt = $db->prepare("SELECT id FROM local_words WHERE word = :word LIMIT 1");
    $checkLinkStmt = $db->prepare("SELECT id FROM topic_words WHERE topic_id = :topic_id AND local_word_id = :local_word_id LIMIT 1");
    $linkStmt = $db->prepare("INSERT INTO topic_words (topic_id, local_word_id) VALUES (:topic_id, :local_word_id)");

    $topicCount = 0;
    $linkedCount = 0;
    $notFoundCount = 0;

    echo "<h2>🌱 Seeding Topics...</h2>";
    echo "<hr>";

    foreach ($topicsData as $topicData) {
        // Insert topic
        $topicStmt->bindParam(':name', $topicData['name']);
        $topicStmt->bindParam(':description', $topicData['description']);
        
        if ($topicStmt->execute()) {
            $topicId = $db->lastInsertId();
            $topicCount++;
            echo "<h3>✅ Chủ đề: {$topicData['name']} (ID: {$topicId})</h3>";
            echo "<ul>";
            
            // Link words to topic
            foreach ($topicData['words'] as $wordName) {
                $getWordStmt->bindParam(':word', $wordName);
                $getWordStmt->execute();
                $result = $getWordStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result) {
                    $wordId = $result['id'];
                    
                    // Check if already linked
                    $checkLinkStmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
                    $checkLinkStmt->bindParam(':local_word_id', $wordId, PDO::PARAM_INT);
                    $checkLinkStmt->execute();
                    
                    if (!$checkLinkStmt->fetch()) {
                        // Not linked yet, so link it
                        $linkStmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
                        $linkStmt->bindParam(':local_word_id', $wordId, PDO::PARAM_INT);
                        
                        if ($linkStmt->execute()) {
                            $linkedCount++;
                            echo "<li>📖 Linked: <strong>{$wordName}</strong> (ID: {$wordId})</li>";
                        }
                    } else {
                        echo "<li>⏭️  Already linked: <strong>{$wordName}</strong></li>";
                    }
                } else {
                    $notFoundCount++;
                    echo "<li>⚠️  Not found in database: <strong>{$wordName}</strong></li>";
                }
            }
            echo "</ul>";
        }
    }

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Hoàn thành Seeding!</h2>";
    echo "<div style='background: #f0f7ff; padding: 20px; border-radius: 8px; border-left: 4px solid #0d6efd;'>";
    echo "<p><strong>📊 Thống kê:</strong></p>";
    echo "<ul>";
    echo "<li>Chủ đề tạo mới: <strong>{$topicCount}</strong></li>";
    echo "<li>Từ được liên kết: <strong>{$linkedCount}</strong></li>";
    if ($notFoundCount > 0) {
        echo "<li>⚠️  Từ không tìm thấy: <strong style='color: orange;'>{$notFoundCount}</strong></li>";
    }
    echo "</ul>";
    echo "</div>";

    echo "<p style='margin-top: 20px;'>";
    echo "<a href='/Vocabulary/public/index.php?route=topics' style='display: inline-block; background: #0d6efd; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 700;'>";
    echo "👉 Xem trang Topics";
    echo "</a>";
    echo "</p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</h2>";
    echo "<pre style='background: #fee; padding: 10px; border-radius: 4px;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}
?>
