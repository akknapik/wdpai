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

$repo = new WorkSessionRepository($conn);
$service = new WorkSessionService($repo);

$idUser = $_SESSION['user_id'];
$isActive = $service->hasActiveSession($idUser);

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
    <h1 class="page-title">HOME</h1>
    <h2 class="section-title">WORK TIME</h2>

    <div class="time-stats">
      <div class="time-row" id="todayRow">TODAY: - min</div>
      <div class="time-row" id="weekRow">THIS WEEK: - min</div>
      <div class="time-row" id="monthRow">THIS MONTH: - min</div>
      <div class="time-row" id="yearRow">THIS YEAR: - min</div>
    </div>

    <form action="./controllers/WorkController.php" method="POST" style="text-align: center;">
      <?php if (!$isActive): ?>
        <input type="hidden" name="action" value="start">
        <button type="submit" class="start-work-btn">START WORK</button>
      <?php else: ?>
        <input type="hidden" name="action" value="stop">
        <button type="submit" class="start-work-btn stop">STOP WORK</button>
      <?php endif; ?>
    </form>

    <div class="work-status">
      <?php if (!$isActive): ?>
        <span class="status">STATUS: NOT WORKING</span>
        <span class="current-session" id="currentSession">CURRENT SESSION: 0 min</span>
      <?php else: ?>
        <span class="status">STATUS: WORKING</span>
        <span class="current-session" id="currentSession">CURRENT SESSION: ??? min</span>
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

  <script>

    function fetchStats() {
      fetch('./controllers/StatsController.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          const dailyStr   = formatTimeInMinutes(data.dailyTime);
          const weeklyStr  = formatTimeInMinutes(data.weeklyTime);
          const monthlyStr = formatTimeInMinutes(data.monthlyTime);
          const yearlyStr  = formatTimeInMinutes(data.yearlyTime);
          const currentStr = formatTimeInMinutes(data.currentSessionTime);

          document.getElementById('todayRow').textContent  = "TODAY: " + dailyStr;
          document.getElementById('weekRow').textContent   = "THIS WEEK: " + weeklyStr;
          document.getElementById('monthRow').textContent  = "THIS MONTH: " + monthlyStr;
          document.getElementById('yearRow').textContent   = "THIS YEAR: " + yearlyStr;
          document.getElementById('currentSession').textContent = "CURRENT SESSION: " + currentStr;
        })
        .catch(error => {
          console.error('Error fetching stats:', error);
        });
    }

    fetchStats();
    setInterval(fetchStats, 30000);

    function formatTimeInMinutes(totalMinutes) {
      const days = Math.floor(totalMinutes / (60 * 24));    
      const leftoverAfterDays = totalMinutes % (60 * 24);

      const hours = Math.floor(leftoverAfterDays / 60);     
      const mins = leftoverAfterDays % 60;                 

      let parts = [];
      if (days > 0) parts.push(days + 'd');
      if (hours > 0) parts.push(hours + 'h');
      if (mins > 0) parts.push(mins + 'min');

      if (parts.length === 0) {
        return '0min';
      }

      return parts.join(' '); 
    }
  </script>

</body>
</html>
