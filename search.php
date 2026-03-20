<?php
include 'db.php';

$q       = trim($_GET['q'] ?? '');
$rows    = [];
$total   = 0;
$searched = false;

if ($q !== '') {
    $searched = true;
    $like = "%$q%";
    $stmt = mysqli_prepare($conn, 
        "SELECT * FROM registrations 
         WHERE status = 'active' 
           AND (name LIKE ? OR email LIKE ? OR course LIKE ? OR gender LIKE ? OR hobbies LIKE ?)
         ORDER BY id DESC"
    );
    mysqli_stmt_bind_param($stmt, "sssss", $like, $like, $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_num_rows($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search — WorkshopReg</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container-wide">

    <div class="page-header">
        <h1>Search Students</h1>
        <p>Filter active registrations by name, email, course, gender, or hobbies.</p>
    </div>

    <div class="card" style="margin-bottom:24px; padding:20px 24px;">
        <form method="GET" action="search.php">
            <div class="search-bar" style="margin-bottom:0;">
                <input type="search" name="q" 
                       placeholder="Search by name, email, course, gender, or hobbies…"
                       value="<?= htmlspecialchars($q) ?>"
                       autofocus>
                <button type="submit" class="btn btn-primary">🔍 Search</button>
                <?php if ($q): ?>
                    <a href="search.php" class="btn btn-secondary">✕ Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if ($searched): ?>
        <?php if ($total > 0): ?>
            <div class="alert alert-info">Found <strong><?= $total ?></strong> result(s) for "<strong><?= htmlspecialchars($q) ?></strong>"</div>
        <?php else: ?>
            <div class="alert alert-error">No results found for "<strong><?= htmlspecialchars($q) ?></strong>". Try a different keyword.</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($searched && $total > 0): ?>
    <div class="card" style="padding:0; overflow:hidden;">
        <div class="table-wrap">
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
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td class="mono"><?= $row['id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                        <td class="mono"><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $row['age'] ?: '—' ?></td>
                        <td><?= htmlspecialchars($row['course'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($row['gender'] ?: '—') ?></td>
                        <td style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
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
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif (!$searched): ?>
    <div class="empty-state" style="padding:40px 0;">
        <div class="icon">🔍</div>
        <p>Enter a keyword above to search for registered students.</p>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
