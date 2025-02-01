<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$leaveId = $data['leave_id'] ?? null;
$newStatus = $data['status'] ?? null;
$managerInfo = $data['manager_info'] ?? '';

if (!$leaveId || !$newStatus) {
    echo json_encode(['success' => false, 'message' => 'Invalid payload']);
    exit;
}

require_once '../db/Database.php';
require_once '../repository/EmployeeDetailsRepository.php';

$database = new Database();
$conn = $database->getConnection();
$repo = new EmployeeDetailsRepository($conn);

try {
    $repo->updateLeaveStatus($leaveId, $newStatus, $managerInfo);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
