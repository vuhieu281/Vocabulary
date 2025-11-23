<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
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
            <div>
                <h1>Dashboard</h1>
                <small style="color: #999;">Chào mừng bạn quay lại!</small>
            </div>
            <div class="admin-info">
                <span>👤 <?php echo $_SESSION['user_name'] ?? 'Admin'; ?></span>
            </div>
        </div>

        <!-- Stats Cards  -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
            <!-- Users Card -->
            <div style="background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(255, 107, 107, 0.25); position: relative; overflow: hidden; min-height: 140px;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 80px; opacity: 0.1;">👤</div>
                <h4 style="font-size: 12px; font-weight: 600; margin-bottom: 10px; opacity: 0.95;">Users</h4>
                <p style="font-size: 32px; font-weight: 700; margin: 0;"><?php echo $stats['total_users']; ?></p>
                <small style="opacity: 0.85;">+<?php echo $stats['new_users_7days']; ?> in last 24 Hours</small>
            </div>

            <!-- Words Card -->
            <div style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(76, 175, 80, 0.25); position: relative; overflow: hidden; min-height: 140px;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 80px; opacity: 0.1;">📗</div>
                <h4 style="font-size: 12px; font-weight: 600; margin-bottom: 10px; opacity: 0.95;">Words</h4>
                <p style="font-size: 32px; font-weight: 700; margin: 0;"><?php echo $stats['total_words']; ?></p>
                <small style="opacity: 0.85;">Total Stored</small>
            </div>

            <!-- Topics Card -->
            <div style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(255, 193, 7, 0.25); position: relative; overflow: hidden; min-height: 140px;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 80px; opacity: 0.1;">📁</div>
                <h4 style="font-size: 12px; font-weight: 600; margin-bottom: 10px; opacity: 0.95;">Topics</h4>
                <p style="font-size: 32px; font-weight: 700; margin: 0;"><?php echo $stats['total_topics']; ?></p>
                <small style="opacity: 0.85;">Available Topics</small>
            </div>

            <!-- Searches Card -->
            <div style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(33, 150, 243, 0.25); position: relative; overflow: hidden; min-height: 140px;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 80px; opacity: 0.1;">🔍</div>
                <h4 style="font-size: 12px; font-weight: 600; margin-bottom: 10px; opacity: 0.95;">Searches</h4>
                <p style="font-size: 32px; font-weight: 700; margin: 0;"><?php echo $stats['total_searches']; ?></p>
                <small style="opacity: 0.85;"><?php echo $stats['searches_7days']; ?> in 7 Days</small>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
            <div class="chart-container" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                <h3 style="display: flex; align-items: center; gap: 12px; color: #2c3e50; font-size: 16px; font-weight: 600; margin-bottom: 20px;">
                    <i class="fas fa-chart-line" style="color: #6f86d6; font-size: 20px;"></i> Thống kê tìm kiếm (7 ngày)
                </h3>
                <canvas id="searchChart" height="250"></canvas>
            </div>

            <div class="top-words" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                <h3 style="display: flex; align-items: center; gap: 12px; color: #2c3e50; font-size: 16px; font-weight: 600; margin-bottom: 20px;">
                    <i class="fas fa-fire" style="color: #ff6b6b; font-size: 20px;"></i> Top 10 Từ vựng được tìm
                </h3>
                <table class="table" style="width: 100%; font-size: 13px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f0f0f0;">
                            <th style="text-align: left; padding: 10px 0; font-weight: 600; color: #666;">Từ vựng</th>
                            <th style="text-align: right; padding: 10px 0; font-weight: 600; color: #666;">Lượt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activityStats['top_searched_words'] as $idx => $word): ?>
                        <tr style="border-bottom: 1px solid #f5f5f5;">
                            <td style="padding: 12px 0;"><strong><?php echo htmlspecialchars($word['word']); ?></strong></td>
                            <td style="text-align: right; padding: 12px 0;">
                                <span style="background: linear-gradient(135deg, #6f86d6 0%, #5a73c0 100%); color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px;">
                                    <?php echo $word['search_count']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="recent-activities">
            <h3><i class="fas fa-list"></i> Hoạt động gần đây</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Hoạt động</th>
                        <th>Nội dung</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($activity['user_name']); ?></strong></td>
                            <td>
                                <span class="activity-badge <?php echo $activity['activity_type']; ?>">
                                    <i class="fas <?php echo $activity['activity_type'] === 'search' ? 'fa-search' : 'fa-star'; ?>"></i>
                                    <?php echo $activity['activity_type'] === 'search' ? 'Tìm kiếm' : 'Lưu từ'; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($activity['target_name']); ?></td>
                            <td><small><?php echo date('d/m/Y H:i', strtotime($activity['activity_date'])); ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; color: #999;">Chưa có hoạt động</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="index.php?route=admin_activities" class="btn btn-primary"><i class="fas fa-list"></i> Xem tất cả hoạt động</a>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ tìm kiếm
const searchDates = <?php echo json_encode(array_map(function($s) { return $s['date']; }, array_reverse($activityStats['searches_by_date']))); ?>;
const searchCounts = <?php echo json_encode(array_map(function($s) { return $s['count']; }, array_reverse($activityStats['searches_by_date']))); ?>;

const ctx = document.getElementById('searchChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: searchDates,
        datasets: [{
            label: 'Lượt tìm kiếm',
            data: searchCounts,
            borderColor: '#6f86d6',
            backgroundColor: 'rgba(111, 134, 214, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#6f86d6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: { size: 13, weight: 'bold' }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

</body>
</html>
