<?php
require 'config/database.php';

$db = (new Database())->connect();

// Đếm tổng từ
$stmt = $db->query('SELECT COUNT(*) as total FROM local_words');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "📊 Tổng từ vựng: " . $result['total'] . "\n\n";

if ($result['total'] > 0) {
    // Lấy 20 từ đầu tiên
    $stmt = $db->query('SELECT id, word FROM local_words LIMIT 20');
    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "📝 20 từ đầu tiên:\n";
    foreach($words as $w) {
        echo "   - " . $w['word'] . " (ID: " . $w['id'] . ")\n";
    }
    echo "\n✅ Bảng local_words đã có dữ liệu!\n";
} else {
    echo "❌ Bảng local_words trống!\n";
    echo "👉 Hãy import từ vựng từ: /Vocabulary/sql/import_oxford.php\n";
}
?>
