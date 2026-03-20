<?php
include 'db.php';

if (isset($_GET['purge'])) {
    $pid = intval($_GET['purge']);
    $stmt = mysqli_prepare($conn, "DELETE FROM registrations WHERE id = ? AND status = 'deleted'");
    mysqli_stmt_bind_param($stmt, "i", $pid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: recyclebin.php?purged=1");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM registrations WHERE status = 'deleted' ORDER BY id DESC");
$total  = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Bin — WorkshopReg</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container-wide">

    <div class="page-header">
        <h1>🗑 Recycle Bin <span class="badge badge-deleted"><?= $total ?> Deleted</span></h1>
        <p>Soft-deleted records. Restore them or permanently remove them from the database.</p>
    </div>

    <?php if (isset($_GET['restored'])): ?>
        <div class="alert alert-success">✓ Record restored successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['purged'])): ?>
        <div class="alert alert-error">🗑 Record permanently deleted.</div>
    <?php endif; ?>

    <div class="top-row">
        <span style="color:var(--text-muted); font-size:0.84rem;">
            ⚠ Permanently deleted records cannot be recovered.
        </span>
        <a href="view.php" class="btn btn-secondary">← Back to Students</a>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div class="table-wrap">
            <?php if ($total > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>Course</th>
                        <th>Gender</th>
                        <th>Hobbies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="mono"><?= $row['id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                        <td class="mono"><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $row['age'] ?: '—' ?></td>
                        <td><?= htmlspecialchars($row['course'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($row['gender'] ?: '—') ?></td>
                        <td style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?= htmlspecialchars($row['hobbies'] ?: '—') ?>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="restore.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">↩ Restore</a>
                                <a href="recyclebin.php?purge=<?= $row['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Permanently delete this record? This cannot be undone.')">✕ Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <div class="icon">🗑</div>
                <p>Recycle bin is empty. No deleted records found.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
