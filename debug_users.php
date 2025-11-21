<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = (new Database())->connect();
    
    // Check all users
    $stmt = $db->query('SELECT id, name, email, role FROM users ORDER BY id');
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check non-admin users
    $stmt = $db->query('SELECT id, name, email, role FROM users WHERE role != "admin" ORDER BY id');
    $nonAdminUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check quiz results
    $stmt = $db->query('SELECT id, user_id, score, total_questions FROM quiz_results LIMIT 10');
    $quizResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'all_users' => $allUsers,
        'non_admin_users_count' => count($nonAdminUsers),
        'non_admin_users' => $nonAdminUsers,
        'quiz_results' => $quizResults
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
