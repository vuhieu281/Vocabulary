<?php
// Trang kết quả quiz

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$percentage = ($quizResult['score'] / $quizResult['total_questions']) * 100;
$scoreColor = $percentage >= 80 ? '#10b981' : ($percentage >= 60 ? '#f59e0b' : '#ef4444');
?>

<div class="quiz-result-container">
    <div class="result-card">
        <div class="result-header" style="border-bottom-color: <?php echo $scoreColor; ?>">
            <div class="score-display">
                <div class="score-circle" style="border-color: <?php echo $scoreColor; ?>; color: <?php echo $scoreColor; ?>">
                    <span class="score-number"><?php echo $quizResult['score']; ?>/<?php echo $quizResult['total_questions']; ?></span>
                    <span class="score-percent"><?php echo round($percentage); ?>%</span>
                </div>
            </div>
            
            <div class="result-message">
                <?php if ($percentage >= 80): ?>
                    <h2>🎉 Tuyệt vời!</h2>
                    <p>Bạn nắm vững kiến thức. Hãy tiếp tục luyện tập!</p>
                <?php elseif ($percentage >= 60): ?>
                    <h2>👍 Tốt!</h2>
                    <p>Bạn đã hiểu được phần lớn nội dung. Hãy ôn lại các câu sai.</p>
                <?php else: ?>
                    <h2>💪 Cố gắng thêm!</h2>
                    <p>Bạn cần ôn tập thêm. Hãy lưu thêm từ và thử lại.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="result-details">
            <div class="detail-item">
                <span class="detail-label">Đúng:</span>
                <span class="detail-value correct"><?php echo $quizResult['score']; ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Sai:</span>
                <span class="detail-value wrong"><?php echo $quizResult['total_questions'] - $quizResult['score']; ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tổng:</span>
                <span class="detail-value"><?php echo $quizResult['total_questions']; ?></span>
            </div>
        </div>

        <div class="result-answers">
            <h3>📝 Xem chi tiết</h3>
            
            <?php foreach ($quizDetails as $index => $detail): ?>
                <div class="answer-item <?php echo $detail['is_correct'] ? 'correct' : 'wrong'; ?>">
                    <div class="answer-header">
                        <div class="answer-number">Câu <?php echo $index + 1; ?></div>
                        <div class="answer-icon">
                            <?php echo $detail['is_correct'] ? '✓' : '✗'; ?>
                        </div>
                    </div>

                    <div class="answer-content">
                        <div class="answer-word">
                            <strong><?php echo htmlspecialchars($detail['word']); ?></strong>
                            <?php if ($detail['ipa']): ?>
                                <span class="ipa"><?php echo htmlspecialchars($detail['ipa']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="answer-info">
                            <div class="info-item">
                                <span class="info-label">Loại từ:</span>
                                <span class="info-value"><?php echo htmlspecialchars($detail['part_of_speech']); ?></span>
                            </div>
                        </div>

                        <div class="answer-comparison">
                            <div class="comparison-row">
                                <span class="comparison-label">Bạn chọn:</span>
                                <span class="comparison-value">
                                    <?php echo htmlspecialchars($detail['user_answer']); ?>
                                </span>
                            </div>
                            <div class="comparison-row <?php echo $detail['is_correct'] ? 'hide' : ''; ?>">
                                <span class="comparison-label">Đáp án đúng:</span>
                                <span class="comparison-value correct">
                                    <?php echo htmlspecialchars($detail['correct_answer']); ?>
                                </span>
                            </div>
                        </div>

                        <?php if ($detail['audio_link']): ?>
                            <div class="answer-audio">
                                <button class="btn-audio-result" onclick="playQuizAudio('<?php echo htmlspecialchars($detail['audio_link']); ?>')">
                                    🔊 Nghe phát âm
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="result-actions">
            <a href="/Vocabulary/public/index.php?route=quiz" class="btn-action btn-retry">
                🔄 Làm Quiz Khác
            </a>
            <a href="/Vocabulary/public/index.php?route=home" class="btn-action btn-home">
                🏠 Về Trang Chủ
            </a>
        </div>
    </div>
</div>

<script>
function playQuizAudio(url) {
    const audio = new Audio(url);
    audio.play();
}
</script>
