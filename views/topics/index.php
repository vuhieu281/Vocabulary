<?php
// views/topics/index.php
?>
<div class="container" style="max-width:1100px;margin:32px auto;">
    <h2 style="color:#0d6efd;margin-bottom:18px;">📚 Topics - Chủ đề học tập</h2>
    <p style="color:#444;margin-bottom:20px;">Khám phá các chủ đề từ vựng. Chọn một chủ đề để xem danh sách từ liên quan.</p>

    <div class="topic-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;">
        <?php foreach ($topics as $topic): ?>
            <div class="topic-card" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 8px 32px rgba(13,110,253,0.06);border:1px solid #eef7ff;transition:all 0.3s ease;">
                <div style="padding:20px;">
                    <h3 style="margin:0 0 8px 0;color:#0d6efd;font-size:1.25rem;"><?php echo htmlspecialchars($topic['name']); ?></h3>
                    <p style="color:#555;margin:0 0 12px;font-size:0.95rem;min-height:44px;line-height:1.5;"><?php echo nl2br(htmlspecialchars(substr($topic['description'] ?? '', 0, 160))); ?><?php if (strlen($topic['description'] ?? '') > 160) echo '...'; ?></p>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;">
                        <div style="color:#0d6efd;font-weight:700;font-size:1.1rem;">📖 <?php echo (int)$topic['count']; ?> từ</div>
                        <a href="/Vocabulary/public/index.php?route=topic_detail&id=<?php echo (int)$topic['id']; ?>" style="background:#0d6efd;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.95rem;transition:all 0.2s ease;display:inline-block;" onmouseover="this.style.background='#0a58ca'" onmouseout="this.style.background='#0d6efd'">Xem từ →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($topics)): ?>
        <div style="text-align:center;padding:60px 20px;color:#999;">
            <p style="font-size:1.2rem;">📭 Không có chủ đề nào.</p>
            <p><a href="/Vocabulary/public/index.php?route=topics" style="color:#0d6efd;text-decoration:none;">Làm mới trang</a></p>
        </div>
    <?php endif; ?>
</div>
