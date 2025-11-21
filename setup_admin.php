<?php
// Script tạo admin user - Chạy một lần
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Setup Admin User</title>";
echo "<style>
    body { font-family: Arial; max-width: 800px; margin: 50px auto; }
    .box { background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0; }
    .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; }
    .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; }
    .info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 4px; }
    code { background: #e9ecef; padding: 2px 6px; border-radius: 3px; }
    h1 { color: #333; }
</style>";
echo "</head>";
echo "<body>";

echo "<h1>🔧 Setup Admin User</h1>";

try {
    $db = new Database();
    $conn = $db->connect();
    
    if (!$conn) {
        echo "<div class='error'>❌ Không thể kết nối database!</div>";
        die();
    }
    
    echo "<div class='success'>✅ Kết nối database thành công!</div>";
    
    // Kiểm tra xem có admin user không
    $stmt = $conn->query("SELECT id, email, role FROM users WHERE role = 'admin' LIMIT 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "<div class='info'>ℹ️ Admin user đã tồn tại!</div>";
        echo "<div class='box'>";
        echo "ID: {$admin['id']}<br>";
        echo "Email: {$admin['email']}<br>";
        echo "Role: {$admin['role']}<br>";
        echo "</div>";
    } else {
        echo "<div class='info'>ℹ️ Tạo admin user mới...</div>";
        
        // Tạo password hash
        $password = 'admin123';
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert admin user
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $result = $insert->execute([
            'Administrator',
            'admin@vocabulary.local',
            $password_hash,
            'admin'
        ]);
        
        if ($result) {
            echo "<div class='success'>✅ Admin user tạo thành công!</div>";
            echo "<div class='box'>";
            echo "<h3>📧 Thông tin đăng nhập:</h3>";
            echo "<code>Email: admin@vocabulary.local</code><br>";
            echo "<code>Password: admin123</code><br>";
            echo "<br>";
            echo "<h3>🔗 Link truy cập:</h3>";
            echo "<a href='public/index.php?route=login' target='_blank'>👉 Đăng nhập tại đây</a><br><br>";
            echo "<a href='public/index.php?route=admin_dashboard' target='_blank'>👉 Admin panel</a>";
            echo "</div>";
        } else {
            echo "<div class='error'>❌ Lỗi tạo admin user: " . json_encode($insert->errorInfo()) . "</div>";
        }
    }
    
    // Hiển thị tất cả users
    echo "<div class='box'>";
    echo "<h3>👥 Danh sách tất cả users:</h3>";
    $stmt = $conn->query("SELECT id, name, email, role, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "<p>Chưa có user nào</p>";
    } else {
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='background: #e9ecef;'><th style='border: 1px solid #dee2e6; padding: 8px;'>ID</th><th style='border: 1px solid #dee2e6; padding: 8px;'>Name</th><th style='border: 1px solid #dee2e6; padding: 8px;'>Email</th><th style='border: 1px solid #dee2e6; padding: 8px;'>Role</th><th style='border: 1px solid #dee2e6; padding: 8px;'>Created</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['id']}</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['name']}</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['email']}</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'><strong>{$user['role']}</strong></td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['created_at']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    echo "</div>";
    
    // Test password
    echo "<div class='box'>";
    echo "<h3>🔐 Test Password:</h3>";
    
    $test_email = 'admin@vocabulary.local';
    $test_password = 'admin123';
    
    $test_user = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $test_user->execute([$test_email]);
    $test_data = $test_user->fetch(PDO::FETCH_ASSOC);
    
    if ($test_data) {
        $verify = password_verify($test_password, $test_data['password']);
        echo "Email: <code>$test_email</code><br>";
        echo "Password: <code>$test_password</code><br>";
        echo "Verify Result: " . ($verify ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "<br>";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ LỖI: " . $e->getMessage() . "</div>";
    echo "<div class='box'>";
    echo "Code: " . $e->getCode() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "</div>";
}

echo "</body>";
echo "</html>";
?>
