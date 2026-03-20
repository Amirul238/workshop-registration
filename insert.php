<?php
include 'db.php';

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$age     = intval($_POST['age'] ?? 0);
$course  = trim($_POST['course'] ?? '');
$gender  = trim($_POST['gender'] ?? '');
$hobbies = isset($_POST['hobbies']) ? implode(', ', $_POST['hobbies']) : '';
$status  = 'active';

if (empty($name) || empty($email)) {
    header("Location: form.html?error=1");
    exit();
}

$stmt = mysqli_prepare($conn, "INSERT INTO registrations (name, email, age, course, gender, hobbies, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssissss", $name, $email, $age, $course, $gender, $hobbies, $status);

if (mysqli_stmt_execute($stmt)) {
    header("Location: view.php?success=1");
} else {
    header("Location: form.html?error=db");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
