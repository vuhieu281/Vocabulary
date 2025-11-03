<?php include __DIR__ . '/../header.php'; ?>
<form method="post" class="card p-4">
    <div class="mb-3">
        <label class="form-label">Mã sinh viên</label>
        <input name="student_code" class="form-control" required value="<?= htmlspecialchars($student['student_code'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Họ và tên</label>
        <input name="full_name" class="form-control" required value="<?= htmlspecialchars($student['full_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($student['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Ngành</label>
        <input name="major" class="form-control" value="<?= htmlspecialchars($student['major'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Khoa</label>
        <select name="department_id" class="form-select">
            <option value="">-- Chọn khoa --</option>
            <?php foreach ($departments as $d): ?>
                <option value="<?= $d['id'] ?>" <?= isset($student['department_id']) && $student['department_id'] == $d['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="d-flex">
        <button type="submit" class="btn btn-primary me-2"><?= isset($student) ? 'Cập nhật' : 'Thêm mới' ?></button>
        <a href="index.php" class="btn btn-secondary">Hủy</a>
    </div>
</form>
<?php include __DIR__ . '/../footer.php'; ?>