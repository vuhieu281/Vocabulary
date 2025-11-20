<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa người dùng - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif; font-size: 14px; color: #333; }
        <?php include __DIR__ . '/admin-styles.php'; ?>
    </style>
</head>
<body>

<div class="admin-container">
    <?php include __DIR__ . '/_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Chỉnh sửa người dùng</h1>
            <a href="index.php?route=admin_users" class="btn btn-secondary">← Quay lại</a>
        </div>

        <div class="form-container">
            <form method="POST" action="/Vocabulary/api/admin_edit_user.php" class="form">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                <div class="form-group">
                    <label for="name">Tên người dùng *</label>
                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>

                <div class="form-group">
                    <label for="role">Vai trò *</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="info-box">
                    <h4>Thông tin khác</h4>
                    <p><strong>ID:</strong> <?php echo $user['id']; ?></p>
                    <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i:s', strtotime($user['created_at'])); ?></p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
                    <a href="index.php?route=admin_users" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
<?php include __DIR__ . '/admin-styles.php'; ?>

.form-container {
    background-color: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 800px;
}

.form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    font-size: 14px;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
}

.info-box {
    background-color: #ecf0f1;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.info-box h4 {
    margin-top: 0;
    color: #2c3e50;
}

.info-box p {
    margin: 8px 0;
    color: #7f8c8d;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
}
</style>

<?php include __DIR__ . '/../footer.php'; ?>
