<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = (new Database())->connect();
    $stmt = $db->query("SELECT id, name, image, created_at FROM topics ORDER BY id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($rows, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>