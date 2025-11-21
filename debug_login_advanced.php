<?php
// Debug: Kiểm tra chi tiết vấn đề đăng nhập
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "=== DEBUG LOGIN ISSUE ===\n\n";

try {
    $db = new Database();
    $conn = $db->connect();
    
    if (!$conn) {
        die("❌ Không thể kết nối database!");
    }
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Kiểm tra xem có users không
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $userCount = $result['count'];
    
    echo "📊 Tổng số users trong database: {$userCount}\n\n";
    
    if ($userCount == 0) {
        echo "❌ VẤN ĐỀ: Không có user nào trong database!\n";
        echo "📝 Cần tạo admin user...\n\n";
        
        // Tạo admin user
        $password_hash = password_hash('admin123', PASSWORD_BCRYPT);
        echo "🔐 Mật khẩu hash được tạo: {$password_hash}\n\n";
        
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $result = $insert->execute([
            'Administrator',
            'admin@vocabulary.local',
            $password_hash,
            'admin'
        ]);
        
        if ($result) {
            echo "✅ Admin user đã được tạo!\n\n";
        } else {
            echo "❌ Lỗi tạo admin user: " . json_encode($insert->errorInfo()) . "\n";
            exit;
        }
    }
    
    // Hiển thị tất cả users
    echo "👥 DANH SÁCH USERS:\n";
    echo "════════════════════════════════════════════════════════\n";
    
    $stmt = $conn->query("SELECT id, name, email, password, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        echo "ID: {$user['id']}\n";
        echo "Name: {$user['name']}\n";
        echo "Email: {$user['email']}\n";
        echo "Role: {$user['role']}\n";
        echo "Password Hash (first 50 chars): " . substr($user['password'], 0, 50) . "...\n";
        echo "════════════════════════════════════════════════════════\n\n";
    }
    
    // Test verify password
    echo "🔐 TEST PASSWORD VERIFICATION:\n";
    echo "════════════════════════════════════════════════════════\n";
    
    $test_password = 'admin123';
    $admin_user = $conn->query("SELECT password FROM users WHERE role = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    
    if ($admin_user) {
        $stored_hash = $admin_user['password'];
        $verify_result = password_verify($test_password, $stored_hash);
        
        echo "Test password: {$test_password}\n";
        echo "Stored hash: {$stored_hash}\n";
        echo "Password verify result: " . ($verify_result ? "✅ TRUE (Mật khẩu đúng)" : "❌ FALSE (Mật khẩu sai)") . "\n\n";
        
        if (!$verify_result) {
            echo "⚠️ Password mismatch! Tạo lại user...\n\n";
            
            // Re-hash password
            $new_hash = password_hash('admin123', PASSWORD_BCRYPT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE role = 'admin'");
            if ($update->execute([$new_hash])) {
                echo "✅ Password đã được cập nhật!\n";
                echo "Hash mới: {$new_hash}\n\n";
            }
        }
    }
    
    // Kiểm tra User model
    echo "🔍 KIỂM TRA USER MODEL:\n";
    echo "════════════════════════════════════════════════════════\n";
    
    require_once __DIR__ . '/models/User.php';
    $userModel = new User();
    
    // Test getByEmail
    $user = $userModel->getByEmail('admin@vocabulary.local');
    
    if ($user) {
        echo "✅ User tìm được bằng getByEmail()\n";
        echo "User data: " . json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        
        // Test password verify
        $pw_verify = password_verify('admin123', $user['password']);
        echo "Password verify: " . ($pw_verify ? "✅ TRUE" : "❌ FALSE") . "\n\n";
    } else {
        echo "❌ Không tìm thấy user!\n\n";
    }
    
    // Test session
    echo "🔓 TEST SESSION:\n";
    echo "════════════════════════════════════════════════════════\n";
    
    session_start();
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Administrator';
    
    echo "✅ Session started\n";
    echo "Session ID: " . session_id() . "\n";
    echo "Session data: " . json_encode($_SESSION) . "\n\n";
    
    echo "════════════════════════════════════════════════════════\n";
    echo "✅ KIỂM TRA HOÀN TẤT!\n\n";
    echo "🔗 Truy cập đăng nhập: http://localhost/Vocabulary/public/index.php?route=login\n";
    echo "📧 Email: admin@vocabulary.local\n";
    echo "🔐 Password: admin123\n\n";
    
    echo "⚠️ Nếu vẫn có vấn đề, hãy xóa cache browser (Ctrl+Shift+Del) rồi thử lại\n";
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
