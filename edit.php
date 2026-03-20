<?php
include 'db.php';

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = intval($_POST['id']);
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $age     = intval($_POST['age']);
    $course  = trim($_POST['course']);
    $gender  = trim($_POST['gender']);
    $hobbies = isset($_POST['hobbies']) ? implode(', ', $_POST['hobbies']) : '';

    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE registrations SET name=?, email=?, age=?, course=?, gender=?, hobbies=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssisssi", $name, $email, $age, $course, $gender, $hobbies, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: view.php?updated=1");
        exit();
    }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM registrations WHERE id = ? AND status = 'active'");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: view.php");
    exit();
}

$selectedHobbies = explode(', ', $row['hobbies']);
$allHobbies = ['Reading', 'Gaming', 'Coding', 'Photography', 'Sports', 'Music', 'Travelling'];
$courses = ['Computer Science','Information Technology','Software Engineering','Data Science','Electrical Engineering','Mechanical Engineering','Business Administration','Other'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record — WorkshopReg</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Edit Registration</h1>
        <p>Editing record ID <span style="color:var(--accent); font-family:'JetBrains Mono',monospace;">#<?= $row['id'] ?></span> — <?= htmlspecialchars($row['name']) ?></p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">⚠ <?= $error ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="edit.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <div class="form-grid">

                <div class="form-group">
                    <label for="name">Full Name <span class="req">*</span></label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="<?= $row['age'] ?>" min="10" max="99">
                </div>

                <div class="form-group">
                    <label for="course">Course / Programme</label>
                    <select id="course" name="course">
                        <option value="">— Select Course —</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c ?>" <?= $row['course']==$c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Gender</label>
                    <div class="radio-group">
                        <?php foreach (['Male','Female','Prefer not to say'] as $g): ?>
                            <label>
                                <input type="radio" name="gender" value="<?= $g ?>" <?= $row['gender']==$g ? 'checked' : '' ?>>
                                <?= $g ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group full">
                    <label>Hobbies</label>
                    <div class="check-group">
                        <?php foreach ($allHobbies as $h): ?>
                            <label>
                                <input type="checkbox" name="hobbies[]" value="<?= $h ?>"
                                    <?= in_array($h, $selectedHobbies) ? 'checked' : '' ?>>
                                <?= $h ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div style="display:flex; gap:12px; margin-top:28px;">
                <button type="submit" class="btn btn-primary">✓ Save Changes</button>
                <a href="view.php" class="btn btn-secondary">✕ Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
