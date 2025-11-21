<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = (new Database())->connect();
    $stmt = $db->query('SELECT id, name, email, role FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total' => count($users),
        'users' => $users
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
