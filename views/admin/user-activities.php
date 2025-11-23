<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử hoạt động người dùng - Admin Panel</title>
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
            <h1>Lịch sử hoạt động của người dùng</h1>
            <a href="index.php?route=admin_users" class="btn btn-secondary">← Quay lại</a>
        </div>

        <div class="content-box">
            <div class="user-info">
                <h3><?php echo htmlspecialchars($userData['name']); ?></h3>
                <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
                <p>Ngày tham gia: <?php echo date('d/m/Y', strtotime($userData['created_at'])); ?></p>
            </div>

            <h3>Hoạt động</h3>
            
            <?php if (empty($activities)): ?>
            <p style="text-align: center; color: #7f8c8d;">Người dùng này chưa có hoạt động nào.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Loại hoạt động</th>
                        <th>Từ vựng</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td>
                            <span class="activity-type <?php echo $activity['activity_type']; ?>">
                                <?php echo $activity['activity_type'] === 'search' ? '🔍 Tìm kiếm' : '💾 Lưu từ'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($activity['target_name']); ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($activity['activity_date'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="index.php?route=admin_user_activities&user_id=<?php echo $user_id; ?>&page=<?php echo $page - 1; ?>" class="btn btn-secondary">← Trước</a>
                <?php endif; ?>

                <span class="page-info">Trang <?php echo $page; ?>/<?php echo $totalPages; ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="index.php?route=admin_user_activities&user_id=<?php echo $user_id; ?>&page=<?php echo $page + 1; ?>" class="btn btn-secondary">Tiếp →</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
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

.user-info {
    background-color: #ecf0f1;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.user-info h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.user-info p {
    margin: 5px 0;
    color: #7f8c8d;
}

.activity-type {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.activity-type.search {
    background-color: #3498db;
    color: white;
}

.activity-type.saved_word {
    background-color: #2ecc71;
    color: white;
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


