<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit;
}

require_once './db/Database.php';
require_once './repository/EmployeeDetailsRepository.php';

$database = new Database();
$conn = $database->getConnection();
$repo = new EmployeeDetailsRepository($conn);

$managerId = $_SESSION['user_id'];

$employeeId = $_GET['id'] ?? '';
if (empty($employeeId)) {
    header("Location: employees.php");
    exit;
}

$userData = $repo->findUserById($employeeId);
if (!$userData) {
    header("Location: employees.php?error=no_such_user");
    exit;
}

$leaves = $repo->findLeavesByUser($employeeId);

$firstname = $_SESSION['firstname'] ?? 'Manager';
$lastname  = $_SESSION['lastname'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Details</title>
  <link rel="stylesheet" href="./css/index.css">
  <link rel="stylesheet" href="./css/employee_details.css">
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
    <h1 class="page-title">EMPLOYEE DETAILS</h1>

    <div class="employee-info">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['firstname'].' '.$userData['lastname']); ?></p>
      <p><strong>Role:</strong> <?php echo htmlspecialchars($userData['role_name'] ?? 'Employee'); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
    </div>

    <h2>All Leaves</h2>
    <div class="leave-list" style="max-height:400px; overflow-y:auto;">
      <?php foreach ($leaves as $leave): ?>
        <?php
          $leaveId   = $leave['id_leave'];
          $typeName  = $leave['type_name']; 
          $startDate = date('j F Y', strtotime($leave['date_start']));
          $endDate   = date('j F Y', strtotime($leave['date_end']));
          $statusName= $leave['status_name']; 
          ?>
        <div 
          class="leave-row"
          data-leave-id="<?php echo $leaveId; ?>"
          data-leave-type="<?php echo htmlspecialchars($typeName); ?>"
          data-date-start="<?php echo $startDate; ?>"
          data-date-end="<?php echo $endDate; ?>"
          data-reason="<?php echo htmlspecialchars($leave['reason'] ?? ''); ?>"
          data-notes="<?php echo htmlspecialchars($leave['additional_notes'] ?? ''); ?>"
          data-status="<?php echo $statusName; ?>"
          data-manager-info="<?php echo htmlspecialchars($leave['manager_info'] ?? ''); ?>"
        >
          <div class="leave-type"><?php echo $typeName; ?></div>
          <div class="leave-dates"><?php echo $startDate . ' - ' . $endDate; ?></div>
          <div class="leave-status <?php echo $statusName; ?>"><?php echo $statusName; ?></div>
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
      &copy;2024; Designed by akknapik
    </p>
  </footer>

  <div class="modal" id="leaveModal" style="display: none;">
    <div class="modal-content">
      <span class="close-btn" id="closeModal">&times;</span>
      <h3>Leave Details</h3>

      <p><strong>Type:</strong> <span id="modalType"></span></p>
      <p><strong>Date:</strong> <span id="modalDates"></span></p>
      <p><strong>Status:</strong> <span id="modalStatus"></span></p>
      <p><strong>Reason:</strong> <span id="modalReason"></span></p>
      <p><strong>Additional Notes:</strong> <span id="modalNotes"></span></p>

      <label for="managerInfo">Manager Info:</label>
      <textarea id="managerInfo" rows="3"></textarea>

      <div class="modal-actions">
        <button id="approveBtn" class="status-btn">Approve</button>
        <button id="rejectBtn" class="status-btn reject">Reject</button>
      </div>

      <input type="hidden" id="modalLeaveId">
    </div>
  </div>

  <script>
    const leaveRows = document.querySelectorAll('.leave-row');
    const modal = document.getElementById('leaveModal');
    const closeModal = document.getElementById('closeModal');

    const modalType = document.getElementById('modalType');
    const modalDates = document.getElementById('modalDates');
    const modalStatus = document.getElementById('modalStatus');
    const modalReason = document.getElementById('modalReason');
    const modalNotes = document.getElementById('modalNotes');
    const managerInfo = document.getElementById('managerInfo');
    const modalLeaveId = document.getElementById('modalLeaveId');

    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');

    leaveRows.forEach(row => {
      row.addEventListener('click', () => {
        const leaveId   = row.getAttribute('data-leave-id');
        const type      = row.getAttribute('data-leave-type');
        const dateStart = row.getAttribute('data-date-start');
        const dateEnd   = row.getAttribute('data-date-end');
        const reason    = row.getAttribute('data-reason');
        const notes     = row.getAttribute('data-notes');
        const status    = row.getAttribute('data-status');
        const manager   = row.getAttribute('data-manager-info');
        const statusInt = row.getAttribute('data-status-int');

        modalLeaveId.value = leaveId;
        modalType.textContent = type;
        modalDates.textContent = dateStart + ' - ' + dateEnd;
        modalStatus.textContent = status;
        modalReason.textContent = reason ? reason : '—';
        modalNotes.textContent = notes ? notes : '—';
        managerInfo.value = manager ? manager : '';

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

    approveBtn.addEventListener('click', () => {
      updateLeaveStatus(modalLeaveId.value, 2, managerInfo.value);
    });
    rejectBtn.addEventListener('click', () => {
      updateLeaveStatus(modalLeaveId.value, 3, managerInfo.value); 
    });

    function updateLeaveStatus(leaveId, newStatus, managerInfo) {
      fetch('./controllers/LeaveDecisionController.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          leave_id: leaveId,
          status: newStatus,
          manager_info: managerInfo
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          alert('Error updating leave: ' + data.message);
        }
      })
      .catch(err => {
        alert('AJAX error: ' + err);
      });
    }
  </script>
</body>
</html>
