<?php
// Trang chi tiết từ vựng - public/word.php (OOP style)
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Word.php';

$db = new Database();
$pdo = $db->getConnection();
$wordModel = new Word($pdo);

$item = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item = $wordModel->getById((int)$_GET['id']);
} elseif (isset($_GET['word'])) {
    $item = $wordModel->searchExact(trim($_GET['word']));
}

if (!$item) {
    // include header/footer to keep site chrome
    if (file_exists(__DIR__ . '/../views/header.php')) include __DIR__ . '/../views/header.php';
    echo '<div style="max-width:900px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;">Không tìm thấy từ vựng.</div>';
    if (file_exists(__DIR__ . '/../views/footer.php')) include __DIR__ . '/../views/footer.php';
    exit;
}

if (file_exists(__DIR__ . '/../views/header.php')) include __DIR__ . '/../views/header.php';
?>

<div class="word-detail-wrapper">
    <main class="word-card word-main">
        <a href="/Vocabulary/public/search.php" class="back-link">&larr; Quay lại</a>
        <h1 class="word-title"><?php echo htmlspecialchars($item['word']); ?> <small class="word-type"><?php echo htmlspecialchars($item['part_of_speech']); ?></small></h1>

        <?php if (!empty($item['ipa'])): ?>
            <div class="word-meta">Phiên âm: <strong><?php echo htmlspecialchars($item['ipa']); ?></strong></div>
        <?php endif; ?>

        <?php if (!empty($item['audio_link'])): ?>
            <div class="word-audio"><audio controls src="<?php echo htmlspecialchars($item['audio_link']); ?>">Trình duyệt không hỗ trợ audio.</audio></div>
        <?php endif; ?>

        <section class="word-senses">
            <h3>Nghĩa / Senses</h3>
            <div class="senses-content">
                <?php
                    $senses = $item['senses'];
                    $decoded = json_decode($senses, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        echo '<ul>';
                        foreach ($decoded as $sense) {
                            if (is_string($sense)) echo '<li>'.nl2br(htmlspecialchars($sense)).'</li>';
                            else echo '<li>'.nl2br(htmlspecialchars(implode(' — ', (array)$sense))).'</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo nl2br(htmlspecialchars($senses));
                    }
                ?>
            </div>
        </section>
    </main>

    <aside class="word-side">
        <h4>Thông tin</h4>
        <p><strong>Level:</strong> <?php echo htmlspecialchars($item['level'] ?: '—'); ?></p>
        <p><strong>Oxford:</strong> <?php if (!empty($item['oxford_url'])) echo '<a href="'.htmlspecialchars($item['oxford_url']).'" target="_blank">Link</a>'; else echo '—'; ?></p>
        <p><strong>ID:</strong> <?php echo (int)$item['id']; ?></p>
    </aside>
</div>

<?php if (file_exists(__DIR__ . '/../views/footer.php')) include __DIR__ . '/../views/footer.php'; ?>
