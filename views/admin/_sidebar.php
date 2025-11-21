<?php
// Admin sidebar component
// Lấy route hiện tại để highlight menu item
$currentRoute = $_GET['route'] ?? 'admin_dashboard';

// Xác định route cha cho từng trang (edit-word, add-word, etc. thì highlight "Words")
$routeParent = [
    'admin_add_word' => 'admin_words',
    'admin_edit_word' => 'admin_words',
    'admin_add_topic' => 'admin_topics',
    'admin_edit_topic' => 'admin_topics',
    'admin_edit_user' => 'admin_users',
    'admin_save_word' => 'admin_words',
    'admin_update_topic' => 'admin_topics',
    'admin_save_topic' => 'admin_topics',
    'admin_user_activities' => 'admin_activities',
];

// Determine active route (nếu route hiện tại có parent, dùng parent)
$activeRoute = $routeParent[$currentRoute] ?? $currentRoute;

$menuItems = [
    ['route' => 'home', 'icon' => 'fas fa-home', 'label' => 'Home'],
    ['route' => 'admin_dashboard', 'icon' => 'fas fa-chart-line', 'label' => 'Dashboard'],
    ['route' => 'admin_users', 'icon' => 'fas fa-users', 'label' => 'Quản lý User'],
    ['route' => 'admin_words', 'icon' => 'fas fa-book', 'label' => 'Quản lý Từ vựng'],
    ['route' => 'admin_topics', 'icon' => 'fas fa-tag', 'label' => 'Quản lý Chủ đề'],
    ['route' => 'admin_activities', 'icon' => 'fas fa-history', 'label' => 'Lịch sử hoạt động'],
];
?>
<!-- Sidebar Navigation -->
<nav class="admin-sidebar">
    <div class="sidebar-header">
        <h2 style="display: flex; align-items: center; gap: 10px; margin: 0; font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -0.5px;">
            <i class="fas fa-shield-alt" style="font-size: 22px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
            <span>Admin Control</span>
        </h2>
    </div>
    <ul class="sidebar-menu">
        <?php foreach ($menuItems as $item): ?>
        <li>
            <a href="index.php?route=<?php echo $item['route']; ?>" class="nav-link <?php echo ($activeRoute === $item['route']) ? 'active' : ''; ?>">
                <i class="<?php echo $item['icon']; ?>"></i>
                <span><?php echo $item['label']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
        <li>
            <a href="index.php?route=logout" class="nav-link logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </li>
    </ul>
</nav>
