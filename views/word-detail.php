<?php
// views/word-detail.php - Trang hiển thị chi tiết từ vựng

// Khởi động session trước khi có output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy ID từ URL
$word_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

require_once '../config/database.php';
require_once '../models/Word.php';

// Kết nối database
$database = new Database();
$db = $database->getConnection();
$word = new Word($db);

// Lấy dữ liệu từ vựng
$word_data = null;
if ($word_id > 0) {
    $word_data = $word->getById($word_id);
}

if (!$word_data) {
    header("Location: ../public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($word_data['word']); ?> - Vocabulary</title>
    <link rel="stylesheet" href="../public/css/home.css">
    <style>
        .detail-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 32px rgba(13,110,253,0.10);
            padding: 40px;
        }
        .word-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            border-bottom: 2px solid #e8f0ff;
            padding-bottom: 20px;
        }
        .word-title {
            flex: 1;
        }
        .word-title h1 {
            color: #0d6efd;
            font-size: 2.8em;
            margin: 0 0 10px 0;
        }
        .word-pronunciation {
            color: #4285f4;
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .word-pos {
            display: inline-block;
            background: #e8f0ff;
            color: #0d6efd;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.95em;
            font-weight: 600;
        }
        .back-btn {
            background: #0d6efd;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1em;
            transition: background 0.2s;
        }
        .back-btn:hover {
            background: #0b5ed7;
        }
        .save-btn {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1em;
            transition: background 0.2s;
        }
        .save-btn:hover {
            background: #218838;
        }
        .save-btn.saved {
            background: #ff9800;
        }
        .save-btn.saved:hover {
            background: #fb8c00;
        }
        .audio-section {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        .audio-btn {
            background: #4285f4;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .audio-btn:hover {
            background: #1a73e8;
        }
        .word-section {
            margin-bottom: 32px;
        }
        .section-title {
            color: #0d6efd;
            font-size: 1.4em;
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e8f0ff;
        }
        .sense-item {
            background: #f9fbff;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
        .sense-text {
            color: #333;
            font-size: 1.05em;
            line-height: 1.6;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e8f0ff;
        }
        .info-label {
            font-weight: 700;
            color: #0d6efd;
            width: 150px;
            min-width: 150px;
        }
        .info-value {
            color: #444;
            flex: 1;
        }
        .level-badge {
            display: inline-block;
            background: #ffeb3b;
            color: #333;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 600;
        }
        .external-link {
            color: #4285f4;
            text-decoration: none;
            font-weight: 600;
        }
        .external-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include '../views/header.php'; ?>
    
    <div class="detail-container">
        <!-- Header -->
        <div class="word-header">
            <div class="word-title">
                <h1><?php echo htmlspecialchars($word_data['word']); ?></h1>
                <?php if ($word_data['ipa']): ?>
                    <div class="word-pronunciation"><?php echo htmlspecialchars($word_data['ipa']); ?></div>
                <?php endif; ?>
                <?php if ($word_data['part_of_speech']): ?>
                    <span class="word-pos"><?php echo htmlspecialchars($word_data['part_of_speech']); ?></span>
                <?php endif; ?>
                
                <!-- Audio Section -->
                <?php if ($word_data['audio_link']): ?>
                    <div class="audio-section">
                        <button class="audio-btn" onclick="playAudio('<?php echo htmlspecialchars($word_data['audio_link']); ?>')">
                            🔊 Nghe phát âm
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <button class="back-btn" onclick="history.back();">← Quay lại</button>
            <button class="save-btn" id="save-btn" onclick="toggleSaveWord();">💾 Lưu từ</button>
        </div>

        <!-- Meaning Section -->
        <?php if ($word_data['senses']): ?>
        <div class="word-section">
            <h2 class="section-title">Định nghĩa</h2>
            <div class="sense-item">
                <div class="sense-text">
                    <?php echo nl2br(htmlspecialchars($word_data['senses'])); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Information Section -->
        <div class="word-section">
            <h2 class="section-title">Thông tin</h2>
            <div class="info-row">
                <div class="info-label">Từ vựng:</div>
                <div class="info-value"><?php echo htmlspecialchars($word_data['word']); ?></div>
            </div>
            <?php if ($word_data['part_of_speech']): ?>
            <div class="info-row">
                <div class="info-label">Loại từ:</div>
                <div class="info-value"><?php echo htmlspecialchars($word_data['part_of_speech']); ?></div>
            </div>
            <?php endif; ?>
            <?php if ($word_data['ipa']): ?>
            <div class="info-row">
                <div class="info-label">Phát âm:</div>
                <div class="info-value"><?php echo htmlspecialchars($word_data['ipa']); ?></div>
            </div>
            <?php endif; ?>
            <?php if ($word_data['level']): ?>
            <div class="info-row">
                <div class="info-label">Cấp độ:</div>
                <div class="info-value">
                    <span class="level-badge"><?php echo htmlspecialchars($word_data['level']); ?></span>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($word_data['oxford_url']): ?>
            <div class="info-row">
                <div class="info-label">Nguồn:</div>
                <div class="info-value">
                    <a href="<?php echo htmlspecialchars($word_data['oxford_url']); ?>" target="_blank" class="external-link">Oxford Dictionary</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Biến lưu trữ trạng thái từ
        let wordId = <?php echo $word_id; ?>;
        let isSaved = false;

        // Load trạng thái lưu từ khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            checkIfWordSaved();
        });

        // Hàm phát âm
        function playAudio(audioUrl) {
            const audio = new Audio(audioUrl);
            audio.play().catch(error => {
                console.error('Lỗi phát âm:', error);
                alert('Không thể phát âm');
            });
        }

        // Hàm kiểm tra từ đã được lưu chưa
        function checkIfWordSaved() {
            fetch(`../api/check_saved_word.php?word_id=${wordId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        isSaved = data.is_saved;
                        updateSaveButton();
                    }
                })
                .catch(error => {
                    console.error('Lỗi kiểm tra từ đã lưu:', error);
                });
        }

        // Hàm toggle lưu/bỏ lưu từ
        function toggleSaveWord() {
            const action = isSaved ? 'remove' : 'save';
            
            fetch('../api/save_word.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `word_id=${wordId}&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isSaved = data.is_saved;
                    updateSaveButton();
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi lưu từ:', error);
                alert('Có lỗi xảy ra');
            });
        }

        // Hàm cập nhật giao diện nút lưu
        function updateSaveButton() {
            const btn = document.getElementById('save-btn');
            if (isSaved) {
                btn.classList.add('saved');
                btn.textContent = '⭐ Đã lưu';
            } else {
                btn.classList.remove('saved');
                btn.textContent = '💾 Lưu từ';
            }
        }
    </script>

    <?php include '../views/footer.php'; ?>
</body>
</html>
