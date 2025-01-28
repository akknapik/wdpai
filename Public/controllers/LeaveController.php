<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$action = $_POST['action'] ?? '';

require_once '../db/Database.php';
require_once '../services/LeaveService.php';

$database = new Database();
$conn = $database->getConnection();

$leaveRepo = new LeaveRepository($conn);
$leaveTypeRepo = new LeaveTypeRepository($conn);
$leaveService = new LeaveService($leaveRepo, $leaveTypeRepo);

$idUser = $_SESSION['user_id'];

if ($action === 'create') {
    $leaveType       = $_POST['leave_type']       ?? '';
    $dateStart       = $_POST['date_start']       ?? '';
    $dateEnd         = $_POST['date_end']         ?? '';
    $reason          = $_POST['reason']          ?? '';
    $additionalNotes = $_POST['additional_notes'] ?? '';

    if (empty($leaveType) || empty($dateStart) || empty($dateEnd)) {
        header("Location: ../new_leave.php?error=empty_fields");
        exit;
    }

    $newLeaveId = $leavesService->createLeave(
        $idUser,
        (int)$leaveType,
        $dateStart,
        $dateEnd,
        $reason,
        $additionalNotes
    );

    header("Location: ../leave.php?message=leave_created");
    exit;
}

header("Location: ../leave.php");
exit;
