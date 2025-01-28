<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../db/Database.php';
require_once '../services/WorkSessionService.php';

$database = new Database();
$conn = $database->getConnection();

$repo = new WorkSessionRepository($conn);
$service = new WorkSessionService($repo);

$userId = $_SESSION['user_id'];

$daily   = $service->getDailyWorkTime($userId);
$weekly  = $service->getWeeklyWorkTime($userId);
$monthly = $service->getMonthlyWorkTime($userId);
$yearly  = $service->getYearlyWorkTime($userId);
$current = $service->getCurrentSessionWorkTime($userId);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'dailyTime'   => $daily,
    'weeklyTime'  => $weekly,
    'monthlyTime' => $monthly,
    'yearlyTime'  => $yearly,
    'currentSessionTime' => $current
]);
exit;
