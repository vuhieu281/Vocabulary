<?php
// views/topics/detail.php
?>
<div class="container" style="max-width:1100px;margin:32px auto;">
    <nav style="margin-bottom:14px;font-size:0.95rem;color:#666;">
        <a href="/Vocabulary/public/index.php?route=topics" style="color:#0d6efd;text-decoration:none;">Topics</a>
        &nbsp;&raquo;&nbsp;
        <span><?php echo htmlspecialchars($topic['name']); ?></span>
    </nav>

    <h2 style="color:#0d6efd;margin-bottom:6px;"><?php echo htmlspecialchars($topic['name']); ?></h2>
    <p style="color:#555;margin-bottom:18px;"><?php echo nl2br(htmlspecialchars($topic['description'] ?? '')); ?></p>

    <div style="background:#fff;padding:18px;border-radius:16px;box-shadow:0 8px 40px rgba(13,110,253,0.06);border:1px solid #eef7ff;">
        <?php if (empty($words)): ?>
            <div style="padding:28px;color:#666;">Không có từ nào trong chủ đề này.</div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <?php foreach ($words as $w): ?>
                    <?php
                        $firstSense = '';
                        if (!empty($w['senses'])) {
                            $decoded = json_decode($w['senses'], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $first = reset($decoded);
                                if (is_string($first)) $firstSense = $first;
                                else $firstSense = implode(' — ', (array)$first);
                            } else {
                                $firstSense = strip_tags($w['senses']);
                            }
                        }

                        $shortMeaning = htmlspecialchars(mb_substr($firstSense,0,180));
                        if (mb_strlen($firstSense) > 180) $shortMeaning .= '...';
                    ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 14px;border-radius:12px;border:1px solid #f4f7fb;">
                        <div style="display:flex;gap:18px;align-items:center;min-width:0;">
                            <div style="min-width:220px;">
                                <a href="/Vocabulary/views/word-detail.php?id=<?php echo (int)$w['id']; ?>" style="color:#0d6efd;font-weight:800;font-size:1.05rem;text-decoration:none;display:inline-block;">
                                    <?php echo htmlspecialchars($w['word']); ?>
                                </a>
                                <div style="color:#6b778c;margin-top:6px;font-size:0.95rem;"><?php echo htmlspecialchars($w['ipa'] ?? ''); ?></div>
                            </div>

                            <div style="color:#444;font-size:0.95rem;min-width:140px;">
                                <span style="display:inline-block;background:#eef7ff;color:#0d6efd;padding:6px 10px;border-radius:10px;font-weight:700;"><?php echo htmlspecialchars($w['part_of_speech'] ?? ''); ?></span>
                            </div>

                            <div style="color:#444;font-size:0.95rem;flex:1;min-width:0;"><?php echo $shortMeaning; ?></div>
                        </div>

                        <div style="margin-left:18px;">
                            <a href="/Vocabulary/views/word-detail.php?id=<?php echo (int)$w['id']; ?>" style="background:#eef7ff;color:#0d6efd;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:700;display:inline-block;">Xem chi tiết từ</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
