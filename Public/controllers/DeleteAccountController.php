<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$action = $_POST['action'] ?? '';
if ($action !== 'delete_account') {
    header("Location: ../settings.php");
    exit;
}

require_once '../db/Database.php';
$database = new Database();
$db = $database->getConnection();

$userId = $_SESSION['user_id'];

try {
    $db->beginTransaction();

    $sqlLeaves = "DELETE FROM leaves WHERE id_user = :userId";
    $stmt = $db->prepare($sqlLeaves);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $sqlSessions = "DELETE FROM work_sessions WHERE id_user = :userId";
    $stmt2 = $db->prepare($sqlSessions);
    $stmt2->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt2->execute();

    $sqlUser = "DELETE FROM users WHERE id_user = :userId";
    $stmt3 = $db->prepare($sqlUser);
    $stmt3->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt3->execute();

    $db->commit();

    session_destroy();

    header("Location: ../register.php");
    exit;

} catch (Exception $e) {
    $db->rollBack();

    error_log("Error deleting account for user $userId: " . $e->getMessage());

    header("Location: ../settings.php?error=delete_failed");
    exit;
}
