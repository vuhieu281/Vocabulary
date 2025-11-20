<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử hoạt động - Admin Panel</title>
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
            <h1>Lịch sử hoạt động</h1>
        </div>

        <div class="content-box">
            <div class="stats-grid">
                <div class="stat-item">
                    <h4>Tìm kiếm trong 7 ngày</h4>
                    <div class="activity-chart">
                        <?php foreach (array_reverse($activityStats['searches_by_date']) as $stat): ?>
                        <div class="chart-bar" style="height: <?php echo ($stat['count'] * 10); ?>px;" title="<?php echo $stat['date']; ?>: <?php echo $stat['count']; ?> lượt">
                            <small><?php echo $stat['count']; ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="stat-item">
                    <h4>Top 10 từ được tìm kiếm</h4>
                    <ul class="top-list">
                        <?php foreach ($activityStats['top_searched_words'] as $index => $word): ?>
                        <li>
                            <span class="rank"><?php echo $index + 1; ?></span>
                            <span class="word"><?php echo htmlspecialchars($word['word']); ?></span>
                            <span class="count"><?php echo $word['search_count']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <hr>

            <h3>Hoạt động gần đây</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Loại hoạt động</th>
                        <th>Từ vựng</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td>
                            <a href="index.php?route=admin_user_activities&user_id=<?php echo $activity['user_id']; ?>">
                                <?php echo htmlspecialchars($activity['user_name']); ?>
                            </a>
                        </td>
                        <td>
                            <span class="activity-type <?php echo $activity['activity_type']; ?>">
                                <?php echo $activity['activity_type'] === 'search' ? '🔍 Tìm kiếm' : '💾 Lưu từ'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?route=word_detail&word=<?php echo urlencode($activity['target_name']); ?>">
                                <?php echo htmlspecialchars($activity['target_name']); ?>
                            </a>
                        </td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($activity['activity_date'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
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

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.stat-item h4 {
    margin-top: 0;
    color: #2c3e50;
}

.activity-chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    height: 200px;
    background-color: #ecf0f1;
    padding: 10px;
    border-radius: 4px;
}

.chart-bar {
    width: 30px;
    background-color: #3498db;
    border-radius: 4px 4px 0 0;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.chart-bar:hover {
    background-color: #2980b9;
}

.chart-bar small {
    position: absolute;
    bottom: -25px;
    left: 50%;
    transform: translateX(-50%);
    font-weight: 600;
    color: #2c3e50;
}

.top-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.top-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ecf0f1;
}

.top-list li:last-child {
    border-bottom: none;
}

.rank {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background-color: #3498db;
    color: white;
    border-radius: 50%;
    font-weight: 600;
    margin-right: 10px;
}

.word {
    flex: 1;
    font-weight: 600;
    color: #2c3e50;
}

.count {
    background-color: #ecf0f1;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
    color: #2c3e50;
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

hr {
    margin: 30px 0;
    border: none;
    border-top: 1px solid #ecf0f1;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/../footer.php'; ?>
