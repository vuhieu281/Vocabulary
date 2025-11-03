<?php include __DIR__ . '/../header.php'; ?>
<a href="?action=create" class="btn btn-success mb-3">+ Thêm sinh viên</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Mã SV</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Ngành</th>
            <th>Khoa</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['student_code']) ?></td>
                <td><?= htmlspecialchars($s['full_name']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= htmlspecialchars($s['major']) ?></td>
                <td><?= htmlspecialchars($s['department_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($s['created_at']) ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="?action=edit&id=<?= $s['id'] ?>">Sửa</a>
                    <a class="btn btn-sm btn-danger" href="?action=delete&id=<?= $s['id'] ?>" onclick="return confirm('Xóa sinh viên này?');">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../footer.php'; ?>