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
                <span class="table-title">Danh sách người dùng (<?php echo $totalUsers; ?>)</span>
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
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-badge <?php echo strtolower($user['role']); ?>">
                                <?php echo $user['role'] === 'admin' ? 'Admin' : 'User'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="index.php?route=admin_edit_user&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                            <a href="index.php?route=admin_user_activities&user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info">Xem hoạt động</a>
                            <form method="POST" action="index.php?route=admin_delete_user&id=<?php echo $user['id']; ?>" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa user này?');">
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="index.php?route=admin_users&page=<?php echo $page - 1; ?>" class="btn btn-secondary">← Trước</a>
                <?php endif; ?>

                <span class="page-info">Trang <?php echo $page; ?>/<?php echo $totalPages; ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="index.php?route=admin_users&page=<?php echo $page + 1; ?>" class="btn btn-secondary">Tiếp →</a>
                <?php endif; ?>
            </div>
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
</style>

</body>
</html>
