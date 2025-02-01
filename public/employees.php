<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit;
}

require_once './db/Database.php';
require_once './repository/EmployeeRepository.php';

$database = new Database();
$conn = $database->getConnection();

$employeeRepo = new EmployeeRepository($conn);

$adminId = $_SESSION['user_id'];
$employees = $employeeRepo->findAllForAdmin($adminId);

$firstname = $_SESSION['firstname'] ?? 'Admin';
$lastname  = $_SESSION['lastname'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employees - Admin Panel</title>
  <link rel="stylesheet" href="./css/index.css">
  <link rel="stylesheet" href="./css/employee.css">
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
        <li><a href="./employees.php">EMPLOYEES</a></li>
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
    <h1 class="page-title">EMPLOYEES</h1>

    <div class="employee-list">
      <?php foreach ($employees as $emp): ?>
        <?php
          $fullName = htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']);
          $pending  = (int)$emp['pending_leaves'];
          $userId   = $emp['id_user']; // Upewnij się, że w zapytaniu SELECT zwracasz id_user
        ?>
        <div class="employee-row"
             onclick="window.location.href='employee_details.php?id=<?php echo $userId; ?>';"
             style="cursor: pointer;">
          <div class="employee-name"><?php echo $fullName; ?></div>
          <div class="employee-pending">
            <?php echo $pending; ?> unanswered leave request<?php echo ($pending !== 1 ? 's' : ''); ?>
          </div>
        </div>
      <?php endforeach; ?>
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
