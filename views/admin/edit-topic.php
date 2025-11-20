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
            <li><a href="index.php?route=admin_words" class="nav-link"><i class="fas fa-book"></i> Quản lý Từ vựng</a></li>
            <li><a href="index.php?route=admin_topics" class="nav-link active"><i class="fas fa-tags"></i> Quản lý Chủ đề</a></li>
            <li><a href="index.php?route=admin_activities" class="nav-link"><i class="fas fa-history"></i> Lịch sử hoạt động</a></li>
            <li><a href="index.php?route=logout" class="nav-link logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Chỉnh sửa chủ đề</h1>
            <a href="index.php?route=admin_topics" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>

        <div class="form-container">
            <form method="POST" action="index.php?route=admin_update_topic" class="form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $topic['id']; ?>">

                <!-- Thông tin chủ đề -->
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <h3 style="margin-top: 0; color: #2c3e50;"><i class="fas fa-info-circle"></i> Thông tin chủ đề</h3>
                    
                    <div class="form-group">
                        <label for="name"><i class="fas fa-tag"></i> Tên chủ đề *</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($topic['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left"></i> Mô tả</label>
                        <textarea id="description" name="description"><?php echo htmlspecialchars($topic['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image"><i class="fas fa-image"></i> Hình ảnh</label>
                        <?php if (!empty($topic['image'])): ?>
                        <div class="current-image">
                            <img src="/Vocabulary/<?php echo htmlspecialchars($topic['image']); ?>" alt="<?php echo htmlspecialchars($topic['name']); ?>">
                            <small>Hình ảnh hiện tại</small>
                        </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Để trống nếu không muốn thay đổi. Định dạng hỗ trợ: JPG, PNG, GIF (Tối đa 2MB)</small>
                    </div>
                </div>

                <!-- Từ vựng trong chủ đề -->
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <h3 style="margin-top: 0; color: #2c3e50;"><i class="fas fa-book"></i> Từ vựng trong chủ đề</h3>
                    
                    <div id="current-words" style="margin-bottom: 20px;">
                        <?php if (!empty($topicWords)): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                                <?php foreach ($topicWords as $word): ?>
                                    <div style="background: white; padding: 12px; border-radius: 6px; border: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                                        <span><i class="fas fa-check" style="color: #27ae60; margin-right: 8px;"></i><?php echo htmlspecialchars($word['word']); ?></span>
                                        <button type="button" class="btn-remove-word" data-word-id="<?php echo $word['id']; ?>" style="background: #e74c3c; color: white; border: none; border-radius: 3px; padding: 4px 8px; cursor: pointer; font-size: 12px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #7f8c8d; font-style: italic;">Chủ đề này chưa có từ vựng nào</p>
                        <?php endif; ?>
                    </div>

                    <div style="border-top: 1px solid #ddd; padding-top: 20px;">
                        <h4 style="margin-top: 0; color: #34495e;"><i class="fas fa-plus-circle"></i> Thêm từ vựng mới (tối đa 10)</h4>
                        <div id="words-container">
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                            <div class="word-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 6px; background: white;">
                                <label for="word_<?php echo $i; ?>" style="font-weight: 600; margin-bottom: 8px; display: block;">
                                    <i class="fas fa-keyboard"></i> Từ vựng <?php echo $i; ?>
                                </label>
                                <input type="text" id="word_<?php echo $i; ?>" name="words[]" placeholder="Nhập từ vựng (hỗ trợ gợi ý)" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" list="wordlist">
                            </div>
                            <?php endfor; ?>
                        </div>

                        <datalist id="wordlist"></datalist>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                    <a href="index.php?route=admin_topics" class="btn btn-secondary"><i class="fas fa-times"></i> Hủy</a>
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
    min-height: 100px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #7f8c8d;
}

.current-image {
    margin-bottom: 15px;
}

.current-image img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 4px;
    margin-bottom: 10px;
}

.current-image small {
    display: block;
    color: #7f8c8d;
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
</style>

<script>
// Tải danh sách từ vựng và cập nhật datalist
let allWords = [];

async function loadAllWords() {
    try {
        const response = await fetch('api/search_words.php?q=');
        const data = await response.json();
        
        if (data.success && data.words.length > 0) {
            allWords = data.words;
            updateWordlist('');
        }
    } catch (error) {
        console.error('Error loading words:', error);
    }
}

function updateWordlist(searchTerm = '') {
    const wordlist = document.getElementById('wordlist');
    wordlist.innerHTML = '';
    
    let filtered = allWords;
    if (searchTerm) {
        const term = searchTerm.toLowerCase();
        filtered = allWords.filter(w => w.word.toLowerCase().includes(term));
    }
    
    filtered.slice(0, 50).forEach(word => {
        const option = document.createElement('option');
        option.value = word.word;
        option.label = word.word;
        wordlist.appendChild(option);
    });
}

// Thêm event listeners cho input fields
document.querySelectorAll('input[name="words[]"]').forEach(input => {
    input.addEventListener('input', function() {
        updateWordlist(this.value);
    });
});

// Xóa từ vựng khỏi chủ đề
document.querySelectorAll('.btn-remove-word').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn chắc chắn muốn xóa từ vựng này khỏi chủ đề?')) {
            return;
        }
        
        const wordId = this.dataset.wordId;
        const topicId = <?php echo $topic['id']; ?>;
        const wordElement = this.closest('.word-item') || this.closest('div');
        
        fetch('/Vocabulary/api/remove_word_from_topic.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `word_id=${wordId}&topic_id=${topicId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Loại bỏ element từ DOM
                wordElement.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    wordElement.remove();
                    
                    // Nếu không còn từ nào, hiển thị thông báo
                    if (document.querySelectorAll('#current-words > div').length === 0) {
                        document.getElementById('current-words').innerHTML = '<p style="color: #7f8c8d; font-style: italic;">Chủ đề này chưa có từ vựng nào</p>';
                    }
                }, 300);
                alert('Xóa thành công');
            } else {
                alert('Xóa thất bại: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
    });
});

// Thêm animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }
`;
document.head.appendChild(style);

// Tải danh sách từ vựng khi trang load
document.addEventListener('DOMContentLoaded', loadAllWords);
</script>

<?php include __DIR__ . '/../footer.php'; ?>
