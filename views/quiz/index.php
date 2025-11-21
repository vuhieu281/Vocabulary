<?php
// views/quiz/index.php - Trang chính quiz

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="quiz-container">
    <div class="quiz-main">
        <div class="quiz-intro">
            <h1>🎯 Quiz Từ Vựng</h1>
            <p>Kiểm tra kiến thức của bạn với các câu hỏi trắc nghiệm thú vị</p>
        </div>

        <?php if ($hasWords): ?>
            <!-- Khi user có từ lưu -->
            <div class="quiz-stats">
                <div class="stat-item">
                    <div class="stat-icon">📚</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $totalWords; ?></div>
                        <div class="stat-label">Từ đã lưu</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">❓</div>
                    <div class="stat-content">
                        <div class="stat-number">10</div>
                        <div class="stat-label">Câu hỏi/Quiz</div>
                    </div>
                </div>
            </div>

            <div class="quiz-actions">
                <a href="/Vocabulary/public/index.php?route=quiz&action=start" class="btn-quiz-start">
                    <span class="btn-icon">▶️</span>
                    Bắt đầu Quiz
                </a>
            </div>

            <div class="quiz-info">
                <h3>📋 Hướng dẫn</h3>
                <ul>
                    <li>Mỗi quiz có 10 câu hỏi được lấy ngẫu nhiên từ các từ bạn đã lưu</li>
                    <li>Các câu hỏi có dạng trắc nghiệm với 4 đáp án</li>
                    <li>Bạn có thể gặp các loại câu hỏi như: loại từ, phiên âm, audio, v.v.</li>
                    <li>Sau khi hoàn thành, bạn sẽ nhận được điểm và xem đáp án</li>
                </ul>
            </div>

        <?php else: ?>
            <!-- Khi user chưa lưu từ nào -->
            <div class="quiz-empty">
                <div class="empty-icon">❌</div>
                <h2>Bạn chưa lưu từ nào</h2>
                <p>Để làm quiz, bạn cần lưu ít nhất 10 từ vựng</p>
                <a href="/Vocabulary/public/index.php?route=home" class="btn-quiz-start">
                    <span class="btn-icon">🔍</span>
                    Tìm kiếm từ vựng
                </a>
            </div>

            <div class="quiz-guide">
                <h3>Cách bắt đầu:</h3>
                <ol>
                    <li>Truy cập trang <strong>Home</strong> hoặc <strong>Search</strong></li>
                    <li>Tìm kiếm các từ vựng bạn muốn học</li>
                    <li>Nhấp nút <strong>Lưu</strong> để lưu từ (cần ít nhất 10 từ)</li>
                    <li>Quay lại trang này và bắt đầu quiz</li>
                </ol>
            </div>
        <?php endif; ?>
    </div>
</div>
