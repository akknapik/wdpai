<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

require_once '../db/Database.php';
require_once '../services/WorkSessionService.php';

$database = new Database();
$conn = $database->getConnection();

$workSessionRepo = new WorkSessionRepository($conn);
$workSessionService = new WorkSessionService($workSessionRepo);


if ($action === 'start') {
    $workSessionService->startWork($id_user);
} elseif ($action === 'stop') {
    $workSessionService->stopWork($id_user);
}

header("Location: ../index.php");
exit;
