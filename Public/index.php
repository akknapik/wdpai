<?php
// home.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Strona główna</title>
</head>
<body>
    <h1>Witaj, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
    <p>Twoje ID to: <?php echo $_SESSION['user_id']; ?>, a Twoja rola to: <?php echo $_SESSION['role']; ?></p>

    <a href="logout.php">Wyloguj</a>
</body>
</html>
