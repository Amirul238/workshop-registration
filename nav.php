<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <a href="view.php" class="logo">Workshop<span>Reg</span></a>
    <div class="nav-links">
        <a href="form.html" class="<?= $current=='form.html'?'active':'' ?>">&#43; Register</a>
        <a href="view.php" class="<?= $current=='view.php'?'active':'' ?>">Students</a>
        <a href="search.php" class="<?= $current=='search.php'?'active':'' ?>">Search</a>
        <a href="recyclebin.php" class="<?= $current=='recyclebin.php'?'active':'' ?>">&#128465; Recycle Bin</a>
    </div>
</nav>
