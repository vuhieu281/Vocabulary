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
        <h2 style="text-align:center; color:#1d65c1; margin-bottom:24px;">Đăng ký</h2>

        <?php if (!empty($error)): ?>
            <div style="color:#d10000; background:#ffe5e5; padding:10px; border-radius:8px; margin-bottom:15px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="index.php?route=register_action" method="POST">

            <label>Họ và tên</label>
            <input type="text" name="name" placeholder="Nhập họ tên..." required
                style="width:100%; padding:12px; margin-bottom:14px; border-radius:8px; border:1px solid #bfd7ff;">

            <label>Email</label>
            <input type="email" name="email" placeholder="Nhập email..." required
                style="width:100%; padding:12px; margin-bottom:14px; border-radius:8px; border:1px solid #bfd7ff;">

            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="Tạo mật khẩu..." required
                style="width:100%; padding:12px; margin-bottom:14px; border-radius:8px; border:1px solid #bfd7ff;">

            <button class="btn"
                style="width:100%; padding:12px; background:#1d65c1; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600;">
                Đăng ký
            </button>

            <p style="text-align:center; margin-top:12px;">
                Đã có tài khoản?
                <a href="index.php?route=login" style="color:#1d65c1; font-weight:600;">Đăng nhập</a>
            </p>
        </form>
    </div>

</div>

<?php include_once __DIR__ . '/../footer.php'; ?>
