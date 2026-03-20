<?php
include 'db.php';

$result = mysqli_query($conn, "SELECT * FROM registrations WHERE status = 'active' ORDER BY id DESC");
$total  = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List — WorkshopReg</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container-wide">

    <div class="page-header">
        <h1>Registered Students <span class="badge badge-active"><?= $total ?> Active</span></h1>
        <p>All active workshop registrations. Use Edit or Delete to manage records.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✓ Student registered successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">✓ Record updated successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-info">🗑 Record moved to Recycle Bin.</div>
    <?php endif; ?>

    <div class="top-row">
        <form action="search.php" method="GET" class="search-bar" style="margin-bottom:0; flex:1; max-width:400px;">
            <input type="search" name="q" placeholder="Quick search by name or email…">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
        <a href="form.html" class="btn btn-primary">+ New Registration</a>
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
                        <td style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($row['hobbies']) ?>">
                            <?= htmlspecialchars($row['hobbies'] ?: '—') ?>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">✏ Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Move this record to Recycle Bin?')">🗑 Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <div class="icon">📋</div>
                <p>No registrations yet. <a href="form.html" style="color:var(--accent);">Add the first one!</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
