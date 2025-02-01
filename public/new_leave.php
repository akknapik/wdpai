<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once './db/Database.php';
require_once './services/LeaveService.php';

$database = new Database();
$conn = $database->getConnection();

$leaveRepo = new LeaveRepository($conn);
$leaveTypeRepo = new LeaveTypeRepository($conn);
$leaveService = new LeaveService($leaveRepo, $leaveTypeRepo);

$idUser = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'] ?? 'Name';
$lastname  = $_SESSION['lastname'] ?? 'Surname';

$leaveTypes = $leaveService->getAllLeaveTypes();

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clockin' - New Leave</title>
  <link rel="stylesheet" href="./css/index.css">
  <link rel="stylesheet" href="./css/leave.css">
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
    <h1 class="page-title">LEAVE</h1>
    <h2 class="section-title">NEW LEAVE</h2>

    <?php if ($error === 'empty_fields'): ?>
      <p style="color:red; text-align:center;">Please fill all required fields (Leave Type, Start Date, End Date)!</p>
    <?php endif; ?>

    <form action="./controllers/LeaveController.php" method="POST" class="leave-form">
      <input type="hidden" name="action" value="create">

      <label for="leave_type">Leave Type</label>
      <select name="leave_type" id="leave_type" required>
        <option value="">-- Select Type --</option>
        <?php foreach ($leaveTypes as $type): ?>
          <option value="<?php echo $type['id_leave_type']; ?>">
            <?php echo htmlspecialchars($type['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="date_start">Start date</label>
      <input type="date" name="date_start" id="date_start" required>

      <label for="date_end">End date</label>
      <input type="date" name="date_end" id="date_end" required>

      <label for="reason">Reason for Leave</label>
      <textarea name="reason" id="reason" rows="2"></textarea>

      <label for="additional_notes">Additional Notes</label>
      <textarea name="additional_notes" id="additional_notes" rows="2"></textarea>

      <button type="submit" class="submit-btn">SUBMIT</button>
    </form>

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
