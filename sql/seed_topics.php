<?php
// sql/seed_topics.php
// Script đơn giản để tạo topic mẫu và gán từ vào topic

require_once __DIR__ . '/../config/database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = (new Database())->connect();
if (!$db) {
    echo "Không thể kết nối database. Kiểm tra config/database.php";
    exit;
}

$topics = [
    ['name' => 'Animals', 'description' => 'Words about animals, pets, wildlife and habitats.'],
    ['name' => 'Travel', 'description' => 'Travel-related vocabulary: airport, hotel, directions and transport.'],
    ['name' => 'Food', 'description' => 'Food, cooking, meals, ingredients and restaurants.'],
    ['name' => 'Technology', 'description' => 'Tech vocabulary: devices, internet, software and gadgets.'],
    ['name' => 'Health', 'description' => 'Health, body, illnesses, and medical terms.'],
    ['name' => 'Education', 'description' => 'School, study, subjects and learning-related words.'],
    ['name' => 'Business', 'description' => 'Business, finance, work and office vocabulary.'],
    ['name' => 'Sports', 'description' => 'Sports, games, players and equipment.'],
    ['name' => 'Nature', 'description' => 'Nature, weather, geography and environment.'],
    ['name' => 'Emotions', 'description' => 'Feelings, emotions and personality-related words.'],
];

echo "<h2>Seeding topics...</h2>";

try {
    // Begin transaction
    $db->beginTransaction();

    // Ensure tables exist
    $checkStmt = $db->query("SHOW TABLES LIKE 'topics'");
    if ($checkStmt->rowCount() === 0) {
        throw new Exception('Bảng `topics` không tồn tại. Hãy chạy sql/create_tables.sql trước.');
    }

    // Insert topics if not exists
    $insertTopic = $db->prepare('INSERT INTO topics (name, description) VALUES (:name, :desc)');
    $selectTopic = $db->prepare('SELECT id FROM topics WHERE name = :name LIMIT 1');

    $topicIds = [];
    foreach ($topics as $t) {
        $selectTopic->execute([':name' => $t['name']]);
        $row = $selectTopic->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $topicIds[$t['name']] = (int)$row['id'];
            echo "Topic exists: " . htmlspecialchars($t['name']) . " (id={$row['id']})<br>";
            continue;
        }

        $insertTopic->execute([':name' => $t['name'], ':desc' => $t['description']]);
        $id = (int)$db->lastInsertId();
        $topicIds[$t['name']] = $id;
        echo "Inserted topic: " . htmlspecialchars($t['name']) . " (id={$id})<br>";
    }

    // Count available words
    $countWordsStmt = $db->query('SELECT COUNT(*) AS c FROM local_words');
    $cntRow = $countWordsStmt->fetch(PDO::FETCH_ASSOC);
    $totalWords = (int)($cntRow['c'] ?? 0);
    echo "<p>Total local_words: {$totalWords}</p>";

    if ($totalWords === 0) {
        throw new Exception('Không có từ trong bảng local_words. Import dữ liệu trước khi gán topics.');
    }

    // Distribute words evenly across topics (up to 30 per topic)
    $topicCount = count($topicIds);
    $perTopic = min(30, max(5, (int)floor($totalWords / $topicCount)));

    $selectWords = $db->prepare('SELECT id FROM local_words ORDER BY id ASC LIMIT :limit OFFSET :offset');
    $selectWords->bindParam(':limit', $perTopic, PDO::PARAM_INT);
    $selectWords->bindParam(':offset', $offset, PDO::PARAM_INT);

    $insertLink = $db->prepare('INSERT INTO topic_words (topic_id, local_word_id) VALUES (:tid, :wid)');
    $checkLink = $db->prepare('SELECT id FROM topic_words WHERE topic_id = :tid AND local_word_id = :wid LIMIT 1');

    $offset = 0;
    foreach ($topicIds as $tname => $tid) {
        // select chunk
        $selectWords->execute(); // ensure no prior bind change
        // need to rebind values via a new statement because bindParam expects variable
        $stmt = $db->prepare('SELECT id FROM local_words ORDER BY id ASC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perTopic, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            // wrap around: if offset exceeds, start from beginning
            $offset = 0;
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $linked = 0;
        foreach ($rows as $r) {
            $wid = (int)$r['id'];
            $checkLink->execute([':tid' => $tid, ':wid' => $wid]);
            if ($checkLink->fetch()) continue;
            $insertLink->execute([':tid' => $tid, ':wid' => $wid]);
            $linked++;
        }

        echo "Linked {$linked} words to topic " . htmlspecialchars($tname) . "<br>";

        $offset += $perTopic;
        if ($offset >= $totalWords) $offset = 0;
    }

    $db->commit();
    echo "<p>Seeding completed.</p>";

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    echo "<p style=\"color:red;\">Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<p><a href=\"/Vocabulary/public/index.php?route=topics\">Go to Topics page</a></p>";

?>
