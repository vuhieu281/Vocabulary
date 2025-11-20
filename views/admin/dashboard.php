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
            <li><a href="index.php?route=admin_dashboard" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="index.php?route=admin_users" class="nav-link"><i class="fas fa-users"></i> Quản lý User</a></li>
            <li><a href="index.php?route=admin_words" class="nav-link"><i class="fas fa-book"></i> Quản lý Từ vựng</a></li>
            <li><a href="index.php?route=admin_topics" class="nav-link"><i class="fas fa-tags"></i> Quản lý Chủ đề</a></li>
            <li><a href="index.php?route=admin_activities" class="nav-link"><i class="fas fa-history"></i> Lịch sử hoạt động</a></li>
            <li><a href="index.php?route=logout" class="nav-link logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </nav>

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

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-content">
                    <h3>Tổng số User</h3>
                    <p class="stat-number"><?php echo $stats['total_users']; ?></p>
                    <small>+<?php echo $stats['new_users_7days']; ?> người trong 7 ngày</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-content">
                    <h3>Từ vựng</h3>
                    <p class="stat-number"><?php echo $stats['total_words']; ?></p>
                    <small>Từ vựng được lưu trữ</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-folder-open"></i></div>
                <div class="stat-content">
                    <h3>Chủ đề</h3>
                    <p class="stat-number"><?php echo $stats['total_topics']; ?></p>
                    <small>Chủ đề có sẵn</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-search"></i></div>
                <div class="stat-content">
                    <h3>Tìm kiếm</h3>
                    <p class="stat-number"><?php echo $stats['total_searches']; ?></p>
                    <small><?php echo $stats['searches_7days']; ?> lần trong 7 ngày</small>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-container">
                <h3><i class="fas fa-chart-area"></i> Thống kê tìm kiếm (7 ngày)</h3>
                <canvas id="searchChart"></canvas>
            </div>

            <div class="top-words">
                <h3><i class="fas fa-fire"></i> Top 10 Từ vựng được tìm</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Từ vựng</th>
                            <th style="text-align: right;">Lượt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activityStats['top_searched_words'] as $idx => $word): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($word['word']); ?></strong></td>
                            <td style="text-align: right;"><span class="badge"><?php echo $word['search_count']; ?></span></td>
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

<?php include __DIR__ . '/../footer.php'; ?>
