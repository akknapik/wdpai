<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$firstname = $_SESSION['firstname'] ?? 'Name';
$lastname  = $_SESSION['lastname'] ?? 'Surname';
$currentEmail = $_SESSION['email'] ?? 'example@example.com';

$error  = $_GET['error']  ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Email</title>
  <link rel="stylesheet" href="./css/index.css">
  <link rel="stylesheet" href="./css/settings.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="img/logo_black.png" alt="Clockin' Logo" class="logo">
    </div>
    <nav>
      <ul>
        <li><a href="./index.php">HOME</a></li>
        <li><a href="./leave.php">LEAVE</a></li>
        <li><a href="./settings.php">SETTINGS</a></li>
      </ul>
    </nav>
    <div class="header-right">
      <div class="user-info">
        <span class="user-name"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></span>
        <a href="logout.php" class="logout-btn">LOG OUT</a>
      </div>
    </div>
  </header>

  <main>
    <h1 class="page-title">CHANGE EMAIL</h1>

    <?php if ($error === 'empty'): ?>
      <p style="color:red; text-align:center;">Please enter a new email!</p>
    <?php elseif ($error === 'invalid'): ?>
      <p style="color:red; text-align:center;">Invalid email format!</p>
    <?php elseif ($error === 'exists'): ?>
      <p style="color:red; text-align:center;">That email is already taken!</p>
    <?php endif; ?>

    <?php if ($success === 'ok'): ?>
      <p style="color:green; text-align:center;">Email changed successfully!</p>
    <?php endif; ?>

    <form action="controllers/SettingsController.php" method="POST" class="change-form">
      <input type="hidden" name="action" value="change_email">
      <label for="new_email">New Email</label>
      <input type="email" name="new_email" id="new_email" required>

      <button type="submit" class="submit-btn">UPDATE EMAIL</button>
    </form>

  </main>

  <footer>
    <div class="footer-links">
      <a href="#">About</a>
      <a href="#">Contact us</a>
      <a href="#">Service</a>
    </div>
    <p class="footer-copy">
      &copy;2024; Designed by akknapik
    </p>
  </footer>
</body>
</html>
