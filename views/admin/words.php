<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Từ vựng - Admin Panel</title>
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
            <h1>Quản lý từ vựng</h1>
            <a href="index.php?route=admin_add_word" class="btn btn-primary">+ Thêm từ vựng</a>
        </div>

        <div class="content-box">
            <div class="table-header">
                <span class="table-title">Danh sách từ vựng (<?php echo $totalWords; ?>)</span>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Từ</th>
                        <th>Loại từ</th>
                        <th>Level</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($words as $word): ?>
                    <tr>
                        <td><?php echo $word['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($word['word']); ?></strong>
                            <br><small style="color: #7f8c8d;"><?php echo htmlspecialchars(substr($word['senses'], 0, 50)); ?>...</small>
                        </td>
                        <td><?php echo htmlspecialchars($word['part_of_speech'] ?? 'N/A'); ?></td>
                        <td><span class="level-badge"><?php echo htmlspecialchars($word['level'] ?? 'N/A'); ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($word['created_at'])); ?></td>
                        <td>
                            <a href="index.php?route=admin_edit_word&id=<?php echo $word['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                            <form method="POST" action="index.php?route=admin_delete_word&id=<?php echo $word['id']; ?>" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa từ này?');">
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
                    <a href="index.php?route=admin_words&page=<?php echo $page - 1; ?>" class="btn btn-secondary">← Trước</a>
                <?php endif; ?>

                <span class="page-info">Trang <?php echo $page; ?>/<?php echo $totalPages; ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="index.php?route=admin_words&page=<?php echo $page + 1; ?>" class="btn btn-secondary">Tiếp →</a>
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

.level-badge {
    display: inline-block;
    background-color: #9b59b6;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
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
