<?php


require_once __DIR__ . '/../config/database.php';

try {
    $db = (new Database())->connect();
    
    // Dữ liệu chủ đề + từ vựng mở rộng (từ oxford_words.csv)
    $topicsData = [
        [
            'name' => 'Động vật',
            'description' => 'Các từ vựng liên quan đến động vật hoang dã, thú cưng, và động vật trang trại.',
            'image' => '/uploads/topics/dongvat.png',
            'words' => ['dog', 'cat', 'elephant', 'lion', 'tiger', 'bear', 'monkey', 'bird', 'fish', 'horse', 'wildlife', 'eagle', 'penguin', 'whale', 'zebra', 'animal', 'pet', 'creature', 'species', 'wildlife', 'habitat', 'predator', 'prey', 'mammal', 'insect', 'reptile', 'amphibian', 'cage', 'den', 'herd', 'flock', 'pack', 'swarm', 'migration', 'endangered', 'extinct', 'fossil', 'evolution', 'adaptation', 'instinct', 'behavior', 'domesticate']
        ],
        [
            'name' => 'Thực vật',
            'description' => 'Các từ vựng về cây cỏ, hoa, cây ăn quả và thực vật.',
            'image' => '/uploads/topics/thucvat.png',
            'words' => ['tree', 'flower', 'leaf', 'root', 'grass', 'bush', 'pine', 'oak', 'rose', 'tulip', 'daisy', 'lily', 'vine', 'moss', 'seed', 'plant', 'herb', 'shrub', 'branch', 'trunk', 'bark', 'petal', 'stem', 'thorn', 'blossom', 'bloom', 'sprout', 'weed', 'fungus', 'algae', 'fern', 'cactus', 'cultivation', 'vegetation', 'ecosystem', 'photosynthesis', 'chlorophyll', 'fertilizer', 'pesticide', 'organic', 'garden', 'orchard']
        ],
        [
            'name' => 'Thực phẩm',
            'description' => 'Các từ vựng liên quan đến thực phẩm, đồ uống, và nấu ăn.',
            'image' => '/uploads/topics/thucpham.png',
            'words' => ['apple', 'bread', 'cheese', 'milk', 'egg', 'rice', 'chicken', 'beef', 'fish', 'vegetable', 'fruit', 'soup', 'salad', 'dessert', 'tea', 'coffee', 'meat', 'pork', 'lamb', 'butter', 'oil', 'salt', 'sugar', 'spice', 'herb', 'sauce', 'pasta', 'cereal', 'grain', 'bean', 'nut', 'berry', 'melon', 'recipe', 'cook', 'bake', 'roast', 'boil', 'fry', 'grill', 'nutrition', 'diet', 'calorie', 'taste']
        ],
        [
            'name' => 'Gia đình',
            'description' => 'Các từ vựng về thành viên gia đình, mối quan hệ và nhà cửa.',
            'image' => '/uploads/topics/giadinh.png',
            'words' => ['mother', 'father', 'sister', 'brother', 'grandmother', 'grandfather', 'uncle', 'aunt', 'cousin', 'son', 'daughter', 'husband', 'wife', 'baby', 'parent', 'child', 'infant', 'toddler', 'adolescent', 'teenager', 'adult', 'elder', 'relative', 'sibling', 'twin', 'stepmother', 'stepfather', 'stepbrother', 'stepsister', 'in-law', 'godparent', 'godchild', 'nephew', 'niece', 'descendant', 'ancestor', 'genealogy', 'inheritance', 'marriage', 'divorce', 'adoption', 'custody', 'bond']
        ],
        [
            'name' => 'Du lịch',
            'description' => 'Các từ vựng hữu ích khi đi du lịch, tham quan, và khám phá.',
            'image' => '/uploads/topics/dulich.png',
            'words' => ['hotel', 'airport', 'ticket', 'passport', 'luggage', 'map', 'suitcase', 'tour', 'guide', 'museum', 'beach', 'mountain', 'village', 'city', 'train', 'plane', 'bus', 'taxi', 'car', 'boat', 'ship', 'cruise', 'resort', 'landmark', 'attraction', 'destination', 'journey', 'adventure', 'expedition', 'excursion', 'tourist', 'traveler', 'visa', 'currency', 'exchange', 'accommodation', 'hostel', 'cottage', 'campsite', 'itinerary', 'souvenir', 'monument', 'cathedral', 'temple', 'palace', 'scenic']
        ],
        [
            'name' => 'Công nghệ',
            'description' => 'Các từ vựng liên quan đến máy tính, điện thoại, internet, và công nghệ.',
            'image' => '/uploads/topics/congnghe.png',
            'words' => ['computer', 'laptop', 'phone', 'software', 'internet', 'email', 'website', 'keyboard', 'mouse', 'screen', 'battery', 'charger', 'camera', 'digital', 'robot', 'tablet', 'monitor', 'processor', 'memory', 'storage', 'application', 'program', 'file', 'folder', 'download', 'upload', 'network', 'server', 'database', 'algorithm', 'virus', 'malware', 'firewall', 'encryption', 'password', 'username', 'interface', 'hardware', 'technology', 'innovation', 'artificial', 'data', 'cloud', 'automation']
        ],
        [
            'name' => 'Thể thao',
            'description' => 'Các từ vựng về các loại thể thao, trò chơi, và hoạt động thể chất.',
            'image' => '/uploads/topics/thethao.png',
            'words' => ['football', 'basketball', 'tennis', 'swimming', 'running', 'cycling', 'volleyball', 'badminton', 'boxing', 'golf', 'skiing', 'skating', 'baseball', 'hockey', 'rugby', 'cricket', 'track', 'field', 'court', 'stadium', 'arena', 'player', 'athlete', 'coach', 'referee', 'umpire', 'champion', 'victory', 'defeat', 'score', 'goal', 'point', 'match', 'tournament', 'competition', 'league', 'team', 'training', 'workout', 'exercise', 'fitness', 'strength', 'endurance', 'medal', 'trophy', 'championship']
        ],
        [
            'name' => 'Kinh doanh',
            'description' => 'Các từ vựng trong môi trường kinh doanh, công sở, và kỹ năng giao tiếp.',
            'image' => '/uploads/topics/kinhdoanh.png',
            'words' => ['office', 'meeting', 'contract', 'profit', 'sales', 'customer', 'employee', 'manager', 'business', 'company', 'market', 'price', 'quality', 'project', 'money', 'budget', 'finance', 'investment', 'stock', 'dividend', 'revenue', 'expense', 'invoice', 'receipt', 'transaction', 'account', 'accounting', 'audit', 'report', 'strategy', 'marketing', 'advertising', 'promotion', 'brand', 'product', 'service', 'client', 'supplier', 'negotiation', 'agreement', 'deadline', 'efficiency', 'productivity', 'enterprise']
        ],
        [
            'name' => 'Giáo dục',
            'description' => 'Các từ vựng liên quan đến trường học, học tập, và các môn học.',
            'image' => '/uploads/topics/giaoduc.png',
            'words' => ['school', 'student', 'teacher', 'book', 'exam', 'class', 'homework', 'lesson', 'subject', 'university', 'library', 'pen', 'notebook', 'paper', 'desk', 'classroom', 'lecture', 'seminar', 'tutorial', 'course', 'curriculum', 'assignment', 'project', 'presentation', 'research', 'academic', 'degree', 'diploma', 'certificate', 'scholarship', 'education', 'training', 'learning', 'instruction', 'knowledge', 'skill', 'ability', 'intelligence', 'achievement', 'grade', 'mark', 'score', 'assessment', 'evaluation', 'qualification']
        ],
        [
            'name' => 'Sức khỏe',
            'description' => 'Các từ vựng về sức khỏe, bệnh tật, và y tế.',
            'image' => '/uploads/topics/suckhoe.png',
            'words' => ['doctor', 'hospital', 'medicine', 'patient', 'nurse', 'health', 'disease', 'pain', 'treatment', 'fever', 'cough', 'exercise', 'vitamin', 'diet', 'sleep', 'illness', 'infection', 'injury', 'wound', 'fracture', 'symptom', 'diagnosis', 'prescription', 'medication', 'therapy', 'surgery', 'operation', 'appointment', 'clinic', 'ambulance', 'emergency', 'vaccination', 'immunization', 'allergy', 'asthma', 'diabetes', 'heart', 'lung', 'brain', 'organ', 'bone', 'muscle', 'blood', 'pressure', 'cholesterol', 'stress', 'wellness']
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
