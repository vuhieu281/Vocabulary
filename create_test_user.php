<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = (new Database())->connect();
    
    // Kiểm tra xem user này đã tồn tại chưa
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute(['testuser@example.com']);
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo json_encode([
            'success' => true,
            'message' => 'User already exists',
            'id' => $existing['id']
        ]);
    } else {
        // Tạo test user
        $stmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $password = password_hash('123456', PASSWORD_BCRYPT);
        $stmt->execute(['Test User', 'testuser@example.com', $password, 'user']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Test user created',
            'id' => $db->lastInsertId(),
            'email' => 'testuser@example.com',
            'password' => '123456'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
