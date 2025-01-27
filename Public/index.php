<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once './db/Database.php';
require_once './services/WorkSessionService.php';

$database = new Database();
$conn = $database->getConnection();

$workSessionRepo = new WorkSessionRepository($conn);
$workSessionService = new WorkSessionService($workSessionRepo);

$idUser = $_SESSION['user_id'];
$activeSession = $workSessionRepo->findActiveSessionByUser($idUser);
$currentWorkActive = $activeSession ? true : false;

$firstname = $_SESSION['firstname'] ?? 'Name';
$lastname  = $_SESSION['lastname'] ?? 'Surname';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clockin' Home</title>
  <link rel="stylesheet" href="./css/index.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="img/logo_black.png" alt="Clockin' Logo" class="logo">
    </div>
    <nav>
      <ul>
        <li><a href="./index.php">HOME</a></li>
        <li><a href="#">LEAVE</a></li>
        <li><a href="#">SETTINGS</a></li>
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
    <h1 class="page-title">HOME</h1>
    <h2 class="section-title">WORK TIME</h2>

    <div class="time-stats">
      <div class="time-row">TODAY: 0 min</div>
      <div class="time-row">THIS WEEK: 0 min</div>
      <div class="time-row">THIS MONTH: 0 min</div>
      <div class="time-row">THIS MONTH: 0 min</div>
    </div>

    <form action="./controllers/WorkController.php" method="POST" style="text-align: center;">
      <?php if (!$currentWorkActive): ?>
        <input type="hidden" name="action" value="start">
        <button type="submit" class="start-work-btn">START WORK</button>
      <?php else: ?>
        <input type="hidden" name="action" value="stop">
        <button type="submit" class="start-work-btn" style="background-color: #e82121;">STOP WORK</button>
      <?php endif; ?>
    </form>

    <div class="work-status">
      <?php if (!$currentWorkActive): ?>
        <span class="status">STATUS: NOT WORKING</span>
        <span class="current-session">CURRENT SESSION: 0 min</span>
      <?php else: ?>
        <span class="status">STATUS: WORKING</span>
        <span class="current-session">CURRENT SESSION: ??? min</span>
      <?php endif; ?>
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
</body>
</html>
