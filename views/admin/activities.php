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
                    <h4><i class="fas fa-chart-bar"></i> Tìm kiếm trong 7 ngày</h4>
                    <div class="activity-chart">
                        <?php foreach (array_reverse($activityStats['searches_by_date']) as $stat): ?>
                        <div class="chart-column">
                            <div class="chart-bar" style="height: <?php echo ($stat['count'] * 8); ?>px;" title="<?php echo $stat['date']; ?>: <?php echo $stat['count']; ?> lượt">
                            </div>
                            <div class="chart-label">
                                <small><?php echo $stat['count']; ?></small>
                                <div class="chart-date"><?php echo date('d/m', strtotime($stat['date'])); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="stat-item">
                    <h4><i class="fas fa-fire"></i> Top 10 từ được tìm kiếm</h4>
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
    margin-bottom: 15px;
}

.activity-chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    height: 280px;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    padding: 20px;
    border-radius: 12px;
    border: 2px solid #e8eef5;
    gap: 12px;
    position: relative;
}

.activity-chart::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, #ddd, transparent);
    border-radius: 0 0 12px 12px;
}

.chart-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    flex: 1;
    height: 100%;
    position: relative;
}

.chart-bar {
    width: 100%;
    background: linear-gradient(to top, #3498db, #5dade2);
    border-radius: 8px 8px 0 0;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-height: 4px;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
}

.chart-bar:hover {
    background: linear-gradient(to top, #2980b9, #3498db);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    transform: translateY(-2px);
}

.chart-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 12px;
    width: 100%;
}

.chart-label small {
    font-weight: 700;
    color: #2c3e50;
    font-size: 13px;
    display: block;
}

.chart-date {
    font-size: 11px;
    color: #7f8c8d;
    margin-top: 4px;
    font-weight: 600;
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
    
    .activity-chart {
        height: 250px;
    }
}
</style>

<?php include __DIR__ . '/../footer.php'; ?>
