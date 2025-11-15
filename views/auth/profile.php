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
            <ul>
                <?php foreach ($savedWords as $w): ?>
                    <li><?= $w['word'] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
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

<?php include_once __DIR__ . '/../footer.php'; ?>
