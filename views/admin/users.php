<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý User - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            font-size: 14px;
            color: #333;
        }
        
        <?php include __DIR__ . '/admin-styles.php'; ?>
    </style>
</head>
<body>

<div class="admin-container">
    <?php include __DIR__ . '/_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Quản lý người dùng</h1>
        </div>

        <div class="content-box">
            <div class="table-header">
                <span class="table-title">Danh sách Admin (<?php echo count(array_filter($users, fn($u) => $u['role'] === 'admin')); ?>)</span>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                                Không có admin nào trong hệ thống
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['role'] === 'admin'): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="role-badge admin">Admin</span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td class="actions-cell">
                                    <a href="index.php?route=admin_edit_user&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="index.php?route=admin_user_activities&user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info">Hoạt động</a>
                                    <form method="POST" action="index.php?route=admin_delete_user&id=<?php echo $user['id']; ?>" style="display: inline;" onsubmit="return confirm('Xóa user này?');">
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- BẢNG USER THƯỜNG -->
        <div class="content-box" style="margin-top: 40px;">
            <div class="table-header">
                <span class="table-title">Danh sách User (<?php echo count(array_filter($users, fn($u) => $u['role'] !== 'admin')); ?>)</span>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Điểm cao nhất</th>
                        <th>Lần làm quiz</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                                Không có user nào trong hệ thống
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['role'] !== 'admin'): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><span class="role-badge user">User</span></td>
                                <td><span class="score-badge"><?php echo (isset($user['highest_score']) && $user['highest_score'] > 0) ? $user['highest_score'] . '/10' : 'N/A'; ?></span></td>
                                <td><span class="attempts-badge"><?php echo isset($user['quiz_attempts']) ? $user['quiz_attempts'] : 0; ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td class="actions-cell">
                                    <a href="index.php?route=admin_edit_user&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="index.php?route=admin_user_activities&user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info">Hoạt động</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<style>
<?php include __DIR__ . '/admin-styles.php'; ?>

.content-box {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.table-title {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.role-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.role-badge.user {
    background-color: #3498db;
    color: white;
}

.role-badge.admin {
    background-color: #e74c3c;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
    margin-right: 5px;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-info {
    background-color: #16a085;
    color: white;
}

.btn-info:hover {
    background-color: #138d75;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.page-info {
    padding: 8px 15px;
    background-color: #ecf0f1;
    border-radius: 4px;
    font-weight: 600;
}

.score-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    background-color: #f39c12;
    color: white;
    font-weight: 600;
    font-size: 12px;
}

.attempts-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    background-color: #9b59b6;
    color: white;
    font-weight: 600;
    font-size: 12px;
}

.avg-score-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    background-color: #1abc9c;
    color: white;
    font-weight: 600;
    font-size: 12px;
}

.actions-cell {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-success {
    background-color: #27ae60;
    color: white;
    text-decoration: none;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    margin-right: 5px;
}

.btn-success:hover {
    background-color: #229954;
}
</style>

</body>
</html>
