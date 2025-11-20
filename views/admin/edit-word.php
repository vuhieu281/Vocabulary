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
            <li><a href="index.php?route=admin_dashboard" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="index.php?route=admin_users" class="nav-link"><i class="fas fa-users"></i> Quản lý User</a></li>
            <li><a href="index.php?route=admin_words" class="nav-link active"><i class="fas fa-book"></i> Quản lý Từ vựng</a></li>
            <li><a href="index.php?route=admin_topics" class="nav-link"><i class="fas fa-tags"></i> Quản lý Chủ đề</a></li>
            <li><a href="index.php?route=admin_activities" class="nav-link"><i class="fas fa-history"></i> Lịch sử hoạt động</a></li>
            <li><a href="index.php?route=logout" class="nav-link logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Chỉnh sửa từ vựng</h1>
            <a href="index.php?route=admin_words" class="btn btn-secondary">← Quay lại</a>
        </div>

        <div class="form-container">
            <form method="POST" action="/Vocabulary/api/admin_edit_word.php" class="form">
                <input type="hidden" name="id" value="<?php echo $word['id']; ?>">

                <div class="form-group">
                    <label for="word">Từ vựng *</label>
                    <input type="text" id="word" name="word" required value="<?php echo htmlspecialchars($word['word']); ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="part_of_speech">Loại từ</label>
                        <select id="part_of_speech" name="part_of_speech">
                            <option value="">-- Chọn loại từ --</option>
                            <option value="noun" <?php echo ($word['part_of_speech'] === 'noun') ? 'selected' : ''; ?>>Danh từ (Noun)</option>
                            <option value="verb" <?php echo ($word['part_of_speech'] === 'verb') ? 'selected' : ''; ?>>Động từ (Verb)</option>
                            <option value="adjective" <?php echo ($word['part_of_speech'] === 'adjective') ? 'selected' : ''; ?>>Tính từ (Adjective)</option>
                            <option value="adverb" <?php echo ($word['part_of_speech'] === 'adverb') ? 'selected' : ''; ?>>Trạng từ (Adverb)</option>
                            <option value="preposition" <?php echo ($word['part_of_speech'] === 'preposition') ? 'selected' : ''; ?>>Giới từ (Preposition)</option>
                            <option value="conjunction" <?php echo ($word['part_of_speech'] === 'conjunction') ? 'selected' : ''; ?>>Liên từ (Conjunction)</option>
                            <option value="pronoun" <?php echo ($word['part_of_speech'] === 'pronoun') ? 'selected' : ''; ?>>Đại từ (Pronoun)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="level">Level</label>
                        <select id="level" name="level">
                            <option value="">-- Chọn level --</option>
                            <option value="A1" <?php echo ($word['level'] === 'A1') ? 'selected' : ''; ?>>A1 (Beginner)</option>
                            <option value="A2" <?php echo ($word['level'] === 'A2') ? 'selected' : ''; ?>>A2 (Elementary)</option>
                            <option value="B1" <?php echo ($word['level'] === 'B1') ? 'selected' : ''; ?>>B1 (Intermediate)</option>
                            <option value="B2" <?php echo ($word['level'] === 'B2') ? 'selected' : ''; ?>>B2 (Upper Intermediate)</option>
                            <option value="C1" <?php echo ($word['level'] === 'C1') ? 'selected' : ''; ?>>C1 (Advanced)</option>
                            <option value="C2" <?php echo ($word['level'] === 'C2') ? 'selected' : ''; ?>>C2 (Mastery)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ipa">IPA (Phát âm)</label>
                    <input type="text" id="ipa" name="ipa" value="<?php echo htmlspecialchars($word['ipa'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="senses">Nghĩa (Senses)</label>
                    <textarea id="senses" name="senses"><?php echo htmlspecialchars($word['senses'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="audio_link">Link âm thanh</label>
                    <input type="url" id="audio_link" name="audio_link" value="<?php echo htmlspecialchars($word['audio_link'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="oxford_url">Oxford URL</label>
                    <input type="url" id="oxford_url" name="oxford_url" value="<?php echo htmlspecialchars($word['oxford_url'] ?? ''); ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
                    <a href="index.php?route=admin_words" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
<?php include __DIR__ . '/admin-styles.php'; ?>

.form-container {
    background-color: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 800px;
}

.form {
    display: flex;
    flex-direction: column;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    font-size: 14px;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/../footer.php'; ?>
