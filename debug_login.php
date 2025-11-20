<?php
// Debug: Kiểm tra kết nối database và tạo admin user
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "=== Kiểm tra Database ===\n\n";

try {
    $db = new Database();
    $conn = $db->connect();
    
    if (!$conn) {
        die("❌ Không thể kết nối database!");
    }
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Kiểm tra users table
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Tổng số users: " . $result['count'] . "\n\n";
    
    // Xem danh sách users
    $stmt = $conn->query("SELECT id, name, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "❌ Không có user nào. Cần tạo admin user!\n\n";
        
        // Tạo admin user
        echo "📝 Tạo admin user...\n";
        
        $password_hash = password_hash('admin123', PASSWORD_BCRYPT);
        
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $result = $insert->execute([
            'Administrator',
            'admin@vocabulary.local',
            $password_hash,
            'admin'
        ]);
        
        if ($result) {
            echo "✅ Tạo admin user thành công!\n\n";
            echo "📧 Email: admin@vocabulary.local\n";
            echo "🔐 Password: admin123\n";
            echo "👤 Role: admin\n\n";
        } else {
            echo "❌ Lỗi khi tạo admin user!\n";
            echo "Error: " . json_encode($insert->errorInfo()) . "\n";
        }
    } else {
        echo "👥 Danh sách users:\n";
        echo "─────────────────────────────────────────\n";
        foreach ($users as $user) {
            echo "ID: {$user['id']}\n";
            echo "Name: {$user['name']}\n";
            echo "Email: {$user['email']}\n";
            echo "Role: {$user['role']}\n";
            echo "─────────────────────────────────────────\n";
        }
    }
    
    echo "\n🔗 Truy cập đăng nhập: http://localhost/Vocabulary/public/index.php?route=login\n";
    echo "🔗 Admin panel: http://localhost/Vocabulary/public/index.php?route=admin_dashboard\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "❌ Code: " . $e->getCode() . "\n";
}
?>
