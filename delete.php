<?php
include 'db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "UPDATE registrations SET status = 'deleted' WHERE id = ? AND status = 'active'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
header("Location: view.php?deleted=1");
exit();
?>
