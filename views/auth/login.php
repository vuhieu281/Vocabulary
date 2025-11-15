<?php
include_once __DIR__ . '/../header.php';
?>

<div style="display:flex; justify-content:center; margin-top:60px;">

    <div style="
        width:420px;
        background:white;
        padding:34px;
        border-radius:14px;
        box-shadow:0 4px 20px rgba(0,0,0,0.07);
        border:1px solid #e6efff;
    ">
        <h2 style="text-align:center; color:#1d65c1; margin-bottom:24px;">Đăng nhập</h2>

        <?php if (!empty($error)): ?>
            <div style="color:#d10000; background:#ffe5e5; padding:10px; border-radius:8px; margin-bottom:15px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="index.php?route=login_action" method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Nhập email..." required
                   style="width:100%; padding:12px; margin:6px 0 14px; border-radius:8px; border:1px solid #bfd7ff;">

            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="Nhập mật khẩu..." required
                   style="width:100%; padding:12px; margin:6px 0 14px; border-radius:8px; border:1px solid #bfd7ff;">

            <button class="btn" style="
                width:100%; padding:12px;
                background:#1d65c1; color:white;
                border:none; border-radius:8px; cursor:pointer; font-weight:600;">
                Đăng nhập
            </button>

            <p style="text-align:center; margin-top:12px;">
                Chưa có tài khoản?
                <a href="index.php?route=register" style="color:#1d65c1; font-weight:600;">Đăng ký</a>
            </p>
        </form>
    </div>

</div>

<?php include_once __DIR__ . '/../footer.php'; ?>
