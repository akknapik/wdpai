<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
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

$leaves = $leaveService->getLeaves($idUser);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clockin' Leave</title>
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
    <h2 class="section-title">LAST LEAVE</h2>

    <div class="leave-list">
      <?php foreach ($leaves as $leaveRow): ?>
        <?php
          $typeName = $leaveService->leaveTypeToString($leaveRow['leave_type']);
          $statusName = $leaveService->statusToString($leaveRow['status']);
          $dateStart = date('j F Y', strtotime($leaveRow['date_start']));
          $dateEnd   = date('j F Y', strtotime($leaveRow['date_end']));
        ?>
        <div class="leave-row"
             data-reason="<?php echo htmlspecialchars($leaveRow['reason'] ?? ''); ?>"
             data-notes="<?php echo htmlspecialchars($leaveRow['additional_notes'] ?? ''); ?>"
             data-manager="<?php echo htmlspecialchars($leaveRow['manager_info'] ?? ''); ?>">
          <div class="leave-type"><?php echo $typeName; ?></div>
          <div class="leave-dates"><?php echo $dateStart; ?> - <?php echo $dateEnd; ?></div>
          <div class="leave-status <?php echo $statusName; ?>">
            <?php echo $statusName; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="new-leave-container">
      <a href="new_leave.php" class="new-leave-btn">NEW LEAVE</a>
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

  <div class="modal" id="detailsModal">
    <div class="modal-content">
      <span class="close-btn" id="closeModal">&times;</span>
      <h3>Leave Details</h3>
      <p><strong>Reason:</strong> <span id="modalReason"></span></p>
      <p><strong>Additional Notes:</strong> <span id="modalNotes"></span></p>
      <p><strong>Manager Info:</strong> <span id="modalManager"></span></p>
    </div>
  </div>

  <script>
    const leaveRows = document.querySelectorAll('.leave-row');
    const modal = document.getElementById('detailsModal');
    const closeModal = document.getElementById('closeModal');

    const modalReason = document.getElementById('modalReason');
    const modalNotes = document.getElementById('modalNotes');
    const modalManager = document.getElementById('modalManager');

    leaveRows.forEach(row => {
      row.addEventListener('click', () => {
        const reason = row.getAttribute('data-reason');
        const notes = row.getAttribute('data-notes');
        const manager = row.getAttribute('data-manager');

        modalReason.textContent = reason ? reason : '—';
        modalNotes.textContent = notes ? notes : '—';
        modalManager.textContent = manager ? manager : '—';

        modal.style.display = 'block';
      });
    });

    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  </script>
</body>
</html>
