<?php
// views/topics/index.php
?>
<div class="topics-page">
    <div class="topics-inner">
        <h2>Topics</h2>
        <p class="lead">Khám phá các chủ đề từ vựng. Chọn một chủ đề để xem danh sách từ liên quan.</p>

        <div class="topic-grid">
            <?php foreach ($topics as $topic): ?>
                <div class="topic-card">
                    <?php if (!empty($topic['image'])): ?>
                        <div class="topic-image-wrapper">
                            <img src="/Vocabulary/<?php echo htmlspecialchars($topic['image']); ?>" alt="<?php echo htmlspecialchars($topic['name']); ?>">
                            <div class="topic-image-overlay"></div>
                        </div>
                    <?php endif; ?>
                    <div class="topic-body">
                        <h3><?php echo htmlspecialchars($topic['name']); ?></h3>
                        <p class="topic-desc"><?php echo nl2br(htmlspecialchars(substr($topic['description'] ?? '', 0, 160))); ?><?php if (strlen($topic['description'] ?? '') > 160) echo '...'; ?></p>
                        <div class="topic-footer">
                            <div class="topic-count"><?php echo (int)$topic['count']; ?> words</div>
                            <a class="btn btn-small" href="/Vocabulary/public/index.php?route=topic_detail&id=<?php echo (int)$topic['id']; ?>">Xem từ vựng</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
/* Topics page styles + subtle animations */
.topics-page { max-width: 1100px; margin: 32px auto; padding: 0 18px; }
.topics-inner h2 { color: #0d6efd; margin-bottom: 8px; font-size: 2em; }
.topics-inner .lead { color:#444; margin-bottom:20px; }
.topic-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
.topic-card { background: #fff; border-radius: 14px; overflow: hidden; border: 1px solid #eef7ff; box-shadow: 0 10px 30px rgba(13,110,253,0.06); transition: transform 0.28s ease, box-shadow 0.28s ease; display:flex; flex-direction:column; }
.topic-card:hover { transform: translateY(-8px) scale(1.01); box-shadow: 0 18px 40px rgba(13,110,253,0.12); }
.topic-image-wrapper { position: relative; width:100%; height:180px; overflow:hidden; }
.topic-image-wrapper img { width:100%; height:100%; object-fit:cover; display:block; transition: transform 0.5s ease; }
.topic-card:hover .topic-image-wrapper img { transform: scale(1.06); }
.topic-image-overlay { position:absolute; left:0; right:0; top:0; bottom:0; background: linear-gradient(180deg, rgba(13,110,253,0.12), rgba(0,0,0,0)); pointer-events:none; }
.topic-body { padding:18px; display:flex; flex-direction:column; flex:1; }
.topic-body h3 { margin:0 0 8px 0; color:#0d6efd; font-size:1.2em; }
.topic-desc { color:#555; margin:0 0 12px; font-size:0.95rem; min-height:44px; }
.topic-footer { display:flex; justify-content:space-between; align-items:center; margin-top:auto; }
.topic-count { color:#0d6efd; font-weight:700; }
.btn.btn-small { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color:#fff; padding:8px 12px; border-radius:8px; text-decoration:none; font-weight:700; }
@media (max-width:480px) { .topic-image-wrapper { height:140px; } }
</style>