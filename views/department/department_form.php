<?php include __DIR__ . '/../header.php'; ?>
<form method="post" class="card p-4">
    <div class="mb-3">
        <label class="form-label">Tên khoa</label>
        <input name="name" class="form-control" required value="<?= htmlspecialchars($dept['name'] ?? '') ?>">
    </div>
    <div class="d-flex">
        <button type="submit" class="btn btn-primary me-2"><?= isset($dept) ? 'Cập nhật' : 'Thêm mới' ?></button>
        <a href="?action=departments" class="btn btn-secondary">Hủy</a>
    </div>
</form>
<?php include __DIR__ . '/../footer.php'; ?>