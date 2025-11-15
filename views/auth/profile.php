<?php include_once __DIR__ . '/../header.php'; ?>

<style>
    body {
        background: #f1f7ff;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding-bottom: 60px;
    }

    .wrapper {
        width: 90%;
        max-width: 900px;
        margin: 40px auto;
    }

    h2.page-title {
        text-align: center;
        color: #1d65c1;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .section {
        background: white;
        padding: 28px 32px;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border: 1px solid #e6efff;
        margin-bottom: 28px;
    }

    .section h3 {
        color: #1d65c1;
        margin-bottom: 14px;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .icon {
        width: 20px;
        opacity: 0.85;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #f9fbff;
        border-radius: 10px;
        overflow: hidden;
    }

    table th {
        background: #1d65c1;
        color: white;
        padding: 12px;
        font-weight: 600;
    }

    table td {
        padding: 10px;
        border-bottom: 1px solid #e6efff;
    }

    ul li {
        margin-bottom: 6px;
        background: #eef4ff;
        padding: 10px 12px;
        border-radius: 8px;
        color: #333;
        font-weight: 500;
    }

    label {
        font-weight: 500;
        margin-top: 10px;
        display: block;
    }

    input {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        margin-bottom: 14px;
        border-radius: 8px;
        border: 1px solid #bfd7ff;
        background: #f9fbff;
        outline: none;
        font-size: 15px;
    }

    input:focus {
        border-color: #1d65c1;
        box-shadow: 0 0 0 2px rgba(29,101,193,0.2);
    }

    .btn {
        padding: 12px 20px;
        background: #1d65c1;
        color: white;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        margin-top: 10px;
    }

    .btn:hover {
        background: #1554a4;
    }

    .logout-btn {
        display: block;
        width: 220px;
        margin: 30px auto;
        text-align: center;
        background: #ff4e4e;
        color: white;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .logout-btn:hover {
        background: #ff6c6c;
    }

    .success, .error {
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .success {
        color: #096b00;
        background: #e6ffed;
    }

    .error {
        color: #b30000;
        background: #ffe5e5;
    }

    .search-history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #eef4ff;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .history-word-info {
        cursor: pointer;
        flex: 1;
    }

    .history-word-info:hover {
        color: #1d65c1;
    }

    .history-word-name {
        font-weight: 600;
        color: #1d65c1;
        font-size: 16px;
    }

    .history-word-type {
        font-size: 12px;
        color: #666;
        margin-top: 2px;
    }

    .history-time {
        font-size: 12px;
        color: #999;
        margin-right: 10px;
    }

    .btn-delete-history {
        background: #ff6b6b;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-delete-history:hover {
        background: #ff5252;
    }

    .btn-clear-history {
        width: 200px;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    .btn-clear-history:hover {
        background: #ff6c6c;
    }

    .saved-word-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #eef4ff;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .saved-word-info {
        cursor: pointer;
        flex: 1;
    }

    .saved-word-info:hover {
        color: #1d65c1;
    }

    .saved-word-name {
        font-weight: 600;
        color: #1d65c1;
        font-size: 16px;
    }

    .saved-word-type {
        font-size: 12px;
        color: #666;
        margin-top: 2px;
    }

    .btn-unsave-word {
        background: #ff6b6b;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-unsave-word:hover {
        background: #ff5252;
    }

</style>


<div class="wrapper">

    <h2 class="page-title">Trang cá nhân</h2>

    <!-- ================= THÔNG TIN TÀI KHOẢN ================= -->
    <div class="section">
        <h3>
            <img class="icon" src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
            Thông tin tài khoản
        </h3>

        <p><strong>Họ tên:</strong> <?= $user['name'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>
    </div>

    <!-- ================= TỪ VỰNG ĐÃ LƯU ================= -->
    <div class="section">
        <h3>
            <img class="icon" src="https://cdn-icons-png.flaticon.com/512/2883/2883875.png">
            Từ vựng đã lưu
        </h3>

        <?php if (empty($savedWords)): ?>
            <p>Bạn chưa lưu từ nào.</p>
        <?php else: ?>
            <div id="saved-words-container">
                <?php foreach ($savedWords as $w): ?>
                    <div class="saved-word-item">
                        <div class="saved-word-info" onclick="viewWord(<?= $w['id'] ?>)">
                            <div class="saved-word-name"><?= htmlspecialchars($w['word']) ?></div>
                            <div class="saved-word-type"><?= htmlspecialchars($w['part_of_speech'] ?? '') ?></div>
                        </div>
                        <button class="btn-unsave-word" onclick="unsaveWord(<?= $w['id'] ?>)">Bỏ lưu</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ================= LỊCH SỬ TÌM KIẾM ================= -->
    <div class="section">
        <h3>
            <img class="icon" src="https://cdn-icons-png.flaticon.com/512/1995/1995467.png">
            Lịch sử tìm kiếm
        </h3>

        <div id="search-history-container">
            <p>Đang tải lịch sử tìm kiếm...</p>
        </div>

        <button class="btn btn-clear-history" style="background: #ff4e4e; margin-top: 15px;">Xóa toàn bộ lịch sử</button>
    </div>

    <!-- ================= KẾT QUẢ QUIZ ================= -->
    <div class="section">
        <h3>
            <img class="icon" src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png">
            Kết quả bài kiểm tra
        </h3>

        <?php if (empty($quizResults)): ?>
            <p>Bạn chưa làm bài kiểm tra nào.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Điểm</th>
                    <th>Số câu</th>
                    <th>Ngày làm</th>
                </tr>

                <?php foreach ($quizResults as $q): ?>
                <tr>
                    <td><?= $q['score'] ?></td>
                    <td><?= $q['total_questions'] ?></td>
                    <td><?= $q['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <!-- ================= ĐỔI MẬT KHẨU ================= -->
    <div class="section">
        <h3>
            <img class="icon" src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png">
            Đổi mật khẩu
        </h3>

        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <form action="index.php?route=change_password_action" method="POST">
            <label>Mật khẩu cũ</label>
            <input type="password" name="old_password" required>

            <label>Mật khẩu mới</label>
            <input type="password" name="new_password" required>

            <button class="btn">Đổi mật khẩu</button>
        </form>
    </div>

    <a href="index.php?route=logout" class="logout-btn">Đăng xuất</a>

</div>

<script>
    // Load search history khi page tải
    document.addEventListener('DOMContentLoaded', function() {
        loadSearchHistory();
        
        // Xử lý nút Xóa toàn bộ lịch sử
        document.querySelector('.btn-clear-history').addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa toàn bộ lịch sử tìm kiếm?')) {
                clearAllHistory();
            }
        });
    });

    function loadSearchHistory() {
        fetch('../api/get_search_history.php?limit=50')
            .then(response => response.json())
            .then(data => {
                console.log('Search history data:', data);
                const container = document.getElementById('search-history-container');
                
                if (!data.success || data.data.length === 0) {
                    container.innerHTML = '<p>Bạn chưa tìm kiếm từ nào.</p>';
                    return;
                }

                let html = '<div>';
                data.data.forEach(item => {
                    const date = new Date(item.searched_at);
                    const timeStr = date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN');
                    
                    html += `
                        <div class="search-history-item">
                            <div class="history-word-info" onclick="viewWord(${item.word_id})">
                                <div class="history-word-name">${escapeHtml(item.word)}</div>
                                <div class="history-word-type">${escapeHtml(item.part_of_speech || '')}</div>
                            </div>
                            <div class="history-time">${timeStr}</div>
                            <button class="btn-delete-history" onclick="deleteHistory(${item.id})">Xóa</button>
                        </div>
                    `;
                });
                html += '</div>';
                
                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading search history:', error);
                document.getElementById('search-history-container').innerHTML = '<p style="color:red;">Lỗi khi tải lịch sử tìm kiếm</p>';
            });
    }

    function deleteHistory(historyId) {
        if (confirm('Bạn có chắc muốn xóa mục này?')) {
            fetch('../api/delete_search_history.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + historyId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadSearchHistory(); // Reload danh sách
                } else {
                    alert('Không thể xóa mục này');
                }
            });
        }
    }

    function clearAllHistory() {
        // Tạo API xóa toàn bộ - tham khảo phần bên dưới
        fetch('../api/clear_all_search_history.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSearchHistory(); // Reload danh sách
                alert('Đã xóa toàn bộ lịch sử tìm kiếm');
            } else {
                alert('Lỗi: ' + data.message);
            }
        });
    }

    function viewWord(wordId) {
        window.location.href = '../views/word-detail.php?id=' + wordId;
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function unsaveWord(wordId) {
        if (confirm('Bạn có chắc muốn bỏ lưu từ này?')) {
            fetch('../api/save_word.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'word_id=' + wordId + '&action=remove'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload trang để cập nhật
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        }
    }

    function viewWord(wordId) {
        window.location.href = '../views/word-detail.php?id=' + wordId;
    }
</script>

<?php include_once __DIR__ . '/../footer.php'; ?>
</script>
