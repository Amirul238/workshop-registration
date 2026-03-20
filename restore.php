<?php
include 'db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "UPDATE registrations SET status = 'active' WHERE id = ? AND status = 'deleted'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
header("Location: recyclebin.php?restored=1");
exit();
?>
