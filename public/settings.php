<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$firstname = $_SESSION['firstname'] ?? 'Name';
$lastname  = $_SESSION['lastname'] ?? 'Surname';
$email     = $_SESSION['email'] ?? 'example@example.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clockin' Settings</title>
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
        <?php if ($_SESSION['role'] == 1): ?>
          <li><a href="./employees.php">EMPLOYEES</a></li>
        <?php endif; ?>
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
    <h1 class="page-title">SETTINGS</h1>

    <div class="settings-profile">
      <p class="profile-label">Your profile</p>
      <h2 class="profile-name"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h2>
      <hr style="width: 80px; margin: 0.5rem auto;">
      <p class="profile-email-label">Email</p>
      <p class="profile-email"><?php echo htmlspecialchars($email); ?></p>
    </div>

    <div class="settings-row">
      <a class="settings-btn" href="change_email.php">CHANGE EMAIL</a>
    </div>

    <div class="settings-row">
      <a class="settings-btn" href="change_password.php">CHANGE PASSWORD</a>
    </div>

    <div class="settings-row">
      <button class="settings-btn" onclick="confirmDelete()">DELETE ACCOUNT</button>
    </div>
  </main>

  <footer>
    <div class="footer-links">
      <a href="#">About</a>
      <a href="#">Contact us</a>
      <a href="#">Service</a>
    </div>
    <p class="footer-copy">
      Copyright ©2025;
      Designed by akknapik
    </p>
  </footer>

  <form id="deleteAccountForm" action="controllers/DeleteAccountController.php" method="POST" style="display:none;">
    <input type="hidden" name="action" value="delete_account">
  </form>

  <script>
    function confirmDelete() {
      const confirmed = confirm("Are you sure you want to delete your account? This action cannot be undone!");
      if (confirmed) {
        document.getElementById('deleteAccountForm').submit();
      }
    }
  </script>
</body>
</html>
