<?php include __DIR__ . '/../header.php'; ?>
<a href="?action=create_department" class="btn btn-success mb-3">+ Thêm khoa</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên khoa</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($departments as $d): ?>
            <tr>
                <td><?= $d['id'] ?></td>
                <td><?= htmlspecialchars($d['name']) ?></td>
                <td><?= htmlspecialchars($d['created_at']) ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="?action=edit_department&id=<?= $d['id'] ?>">Sửa</a>
                    <a class="btn btn-sm btn-danger" href="?action=delete_department&id=<?= $d['id'] ?>" onclick="return confirm('Xóa khoa này?');">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../footer.php'; ?>