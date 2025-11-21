<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = (new Database())->connect();
    
    // Kiểm tra quiz_results table
    $stmt = $db->query('SELECT * FROM quiz_results LIMIT 5');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'quiz_results_count' => count($results),
        'latest_results' => $results
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
