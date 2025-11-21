<?php
/**
 * Seed Topics & Topic Words Data
 * Tạo 10 chủ đề và link 15 từ vựng từ database cho mỗi chủ đề
 */

require_once __DIR__ . '/../config/database.php';

try {
    $db = (new Database())->connect();
    
    // Dữ liệu chủ đề + từ vựng (chỉ lấy từ đã có trong local_words)
    $topicsData = [
        [
            'name' => 'Động vật',
            'description' => 'Các từ vựng liên quan đến động vật hoang dã, thú cưng, và động vật trang trại.',
            'image' => '/Vocabulary/public/images/topics/animals.jpg',
            'words' => ['dog', 'cat', 'elephant', 'lion', 'tiger', 'bear', 'monkey', 'bird', 'fish', 'horse', 'rabbit', 'eagle', 'penguin', 'whale', 'zebra']
        ],
        [
            'name' => 'Thực vật',
            'description' => 'Các từ vựng về cây cỏ, hoa, cây ăn quả và thực vật.',
            'image' => '/Vocabulary/public/images/topics/plants.jpg',
            'words' => ['tree', 'flower', 'leaf', 'root', 'grass', 'bush', 'pine', 'oak', 'rose', 'tulip', 'daisy', 'lily', 'vine', 'moss', 'seed']
        ],
        [
            'name' => 'Thực phẩm',
            'description' => 'Các từ vựng liên quan đến thực phẩm, đồ uống, và nấu ăn.',
            'image' => '/Vocabulary/public/images/topics/food.jpg',
            'words' => ['apple', 'bread', 'cheese', 'milk', 'egg', 'rice', 'chicken', 'beef', 'fish', 'vegetable', 'fruit', 'soup', 'salad', 'dessert', 'tea']
        ],
        [
            'name' => 'Gia đình',
            'description' => 'Các từ vựng về thành viên gia đình, mối quan hệ và nhà cửa.',
            'image' => '/Vocabulary/public/images/topics/family.jpg',
            'words' => ['mother', 'father', 'sister', 'brother', 'grandmother', 'grandfather', 'uncle', 'aunt', 'cousin', 'son', 'daughter', 'husband', 'wife', 'baby', 'parent']
        ],
        [
            'name' => 'Du lịch',
            'description' => 'Các từ vựng hữu ích khi đi du lịch, tham quan, và khám phá.',
            'image' => '/Vocabulary/public/images/topics/travel.jpg',
            'words' => ['hotel', 'airport', 'ticket', 'passport', 'luggage', 'map', 'suitcase', 'tour', 'guide', 'museum', 'beach', 'mountain', 'village', 'city', 'train']
        ],
        [
            'name' => 'Công nghệ',
            'description' => 'Các từ vựng liên quan đến máy tính, điện thoại, internet, và công nghệ.',
            'image' => '/Vocabulary/public/images/topics/technology.jpg',
            'words' => ['computer', 'laptop', 'phone', 'software', 'internet', 'email', 'website', 'keyboard', 'mouse', 'screen', 'battery', 'charger', 'camera', 'digital', 'robot']
        ],
        [
            'name' => 'Thể thao',
            'description' => 'Các từ vựng về các loại thể thao, trò chơi, và hoạt động thể chất.',
            'image' => '/Vocabulary/public/images/topics/sports.jpg',
            'words' => ['football', 'basketball', 'tennis', 'swimming', 'running', 'cycling', 'volleyball', 'badminton', 'boxing', 'golf', 'skiing', 'skating', 'baseball', 'hockey', 'rugby']
        ],
        [
            'name' => 'Kinh doanh',
            'description' => 'Các từ vựng trong môi trường kinh doanh, công sở, và kỹ năng giao tiếp.',
            'image' => '/Vocabulary/public/images/topics/business.jpg',
            'words' => ['office', 'meeting', 'contract', 'profit', 'sales', 'customer', 'employee', 'manager', 'business', 'company', 'market', 'price', 'quality', 'project', 'money']
        ],
        [
            'name' => 'Giáo dục',
            'description' => 'Các từ vựng liên quan đến trường học, học tập, và các môn học.',
            'image' => '/Vocabulary/public/images/topics/education.jpg',
            'words' => ['school', 'student', 'teacher', 'book', 'exam', 'class', 'homework', 'lesson', 'subject', 'university', 'library', 'pen', 'notebook', 'paper', 'desk']
        ],
        [
            'name' => 'Sức khỏe',
            'description' => 'Các từ vựng về sức khỏe, bệnh tật, và y tế.',
            'image' => '/Vocabulary/public/images/topics/health.jpg',
            'words' => ['doctor', 'hospital', 'medicine', 'patient', 'nurse', 'health', 'disease', 'pain', 'treatment', 'fever', 'cough', 'exercise', 'vitamin', 'diet', 'sleep']
        ]
    ];

    // 1. Insert Topics
    $topicQuery = "INSERT INTO topics (name, description, image) VALUES (:name, :description, :image)";
    $topicStmt = $db->prepare($topicQuery);
    
    // 2. Query để lấy word_id từ local_words
    $getWordQuery = "SELECT id FROM local_words WHERE word = :word LIMIT 1";
    $getWordStmt = $db->prepare($getWordQuery);
    
    // 3. Link word to topic
    $linkQuery = "INSERT INTO topic_words (topic_id, local_word_id) VALUES (:topic_id, :word_id) ON DUPLICATE KEY UPDATE topic_id=topic_id";
    $linkStmt = $db->prepare($linkQuery);
    
    $topicCount = 0;
    $linkCount = 0;
    $notFoundCount = 0;
    
    foreach ($topicsData as $topicData) {
        // Insert topic
        $topicStmt->bindParam(':name', $topicData['name']);
        $topicStmt->bindParam(':description', $topicData['description']);
        $topicStmt->bindParam(':image', $topicData['image']);
        
        if ($topicStmt->execute()) {
            $topicId = $db->lastInsertId();
            $topicCount++;
            echo "✅ Chủ đề: {$topicData['name']} (ID: $topicId)<br>";
            
            // Link existing words from local_words for this topic
            foreach ($topicData['words'] as $word) {
                $getWordStmt->bindParam(':word', $word);
                $getWordStmt->execute();
                $result = $getWordStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result) {
                    $wordId = $result['id'];
                    $linkStmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
                    $linkStmt->bindParam(':word_id', $wordId, PDO::PARAM_INT);
                    
                    if ($linkStmt->execute()) {
                        $linkCount++;
                    }
                } else {
                    $notFoundCount++;
                    echo "⚠️  Không tìm thấy từ '{$word}' trong database<br>";
                }
            }
        }
    }
    
    echo "<br><strong style='color: green;'>✅ Hoàn thành!</strong><br>";
    echo "📌 Chủ đề: $topicCount<br>";
    echo "🔗 Liên kết từ: $linkCount<br>";
    if ($notFoundCount > 0) {
        echo "⚠️  Từ không tìm thấy: $notFoundCount<br>";
    }
    echo "<br><a href='/Vocabulary/public/index.php?route=topics'>👉 Xem trang Topics</a>";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}
