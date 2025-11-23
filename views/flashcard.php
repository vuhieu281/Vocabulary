<?php
// Trang chính flashcard

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="flashcard-container">
    <div class="flashcard-main">
        <div class="flashcard-intro">
            <h1>Học Flashcard</h1>
            <p>Cải thiện từ vựng của bạn một cách vui vẻ và hiệu quả với các flashcard tương tác</p>
        </div>

        <?php if ($hasWords): ?>
            <div class="flashcard-actions">
                <a href="/Vocabulary/public/index.php?route=flashcard&action=learn" class="btn-primary">
                    <span class="btn-icon">▶</span>
                    Bắt đầu học
                </a>
            </div>

            <div class="flashcard-tips">
                <h3>💡 Mẹo học tập</h3>
                <ul>
                    <li>Nhấp vào flashcard để lật mặt</li>
                    <li>Sử dụng các phím mũi tên để điều hướng</li>
                    <li>Nghe phát âm audio để cải thiện kỹ năng nói</li>
                    <li>Lặp lại các từ khó cho đến khi ghi nhớ</li>
                </ul>
            </div>

        <?php else: ?>
            <div class="flashcard-empty">
                <div class="empty-icon">📭</div>
                <h2>Bạn chưa lưu từ nào</h2>
                <p>Để bắt đầu học flashcard, hãy tìm kiếm và lưu các từ vựng</p>
                <a href="/Vocabulary/public/index.php?route=home" class="btn-primary">
                    <span class="btn-icon">🔍</span>
                    Tìm kiếm từ vựng
                </a>
            </div>

            <div class="flashcard-guide">
                <h3>Cách bắt đầu:</h3>
                <ol>
                    <li>Truy cập trang <strong>Home</strong> hoặc <strong>Search</strong></li>
                    <li>Tìm kiếm các từ vựng bạn muốn học</li>
                    <li>Nhấp nút <strong>Lưu</strong> để lưu từ</li>
                    <li>Quay lại trang này và bắt đầu học</li>
                </ol>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
</script>
