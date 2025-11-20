<?php include __DIR__ . '/../header.php'; ?>
<link rel="stylesheet" href="css/admin-new.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="admin-container">
    <!-- Sidebar Navigation -->
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-cogs"></i> Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php?route=admin_dashboard" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="index.php?route=admin_users" class="nav-link active"><i class="fas fa-users"></i> Quản lý User</a></li>
            <li><a href="index.php?route=admin_words" class="nav-link"><i class="fas fa-book"></i> Quản lý Từ vựng</a></li>
            <li><a href="index.php?route=admin_topics" class="nav-link"><i class="fas fa-tags"></i> Quản lý Chủ đề</a></li>
            <li><a href="index.php?route=admin_activities" class="nav-link"><i class="fas fa-history"></i> Lịch sử hoạt động</a></li>
            <li><a href="index.php?route=logout" class="nav-link logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </nav>

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

<?php include __DIR__ . '/../footer.php'; ?>
