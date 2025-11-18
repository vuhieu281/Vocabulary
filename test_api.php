<?php
// test_api.php - File test để kiểm tra API autocomplete

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Test API Autocomplete</h1>";

// Test từ khóa khác nhau
$terms = ['ha', 'ab', 'th'];

foreach ($terms as $term) {
    echo "<h2>Term: '$term'</h2>";
    echo "<p>URL: http://localhost/Vocabulary/api/ajax_autocomplete.php?term=" . urlencode($term) . "</p>";
    
    // Include và gọi trực tiếp
    require_once './config/database.php';
    require_once './models/Word.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $word = new Word($db);
    
    $results = $word->autocomplete($term);
    
    echo "<pre>";
    echo "Kết quả: " . json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";
}
?>
