<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$action = $_POST['action'] ?? '';

require_once '../db/Database.php';
require_once '../repository/UserRepository.php';

$database = new Database();
$db = $database->getConnection();
$usersRepo = new UserRepository($db);

$userId = $_SESSION['user_id'];

if ($action === 'change_email') {
    $newEmail = $_POST['new_email'] ?? '';

    if (empty($newEmail)) {
        header("Location: ../change_email.php?error=empty");
        exit;
    }
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../change_email.php?error=invalid");
        exit;
    }

    if ($usersRepo->emailExists($newEmail)) {
        header("Location: ../change_email.php?error=exists");
        exit;
    }

    $usersRepo->updateEmail($userId, $newEmail);

    $_SESSION['email'] = $newEmail;

    header("Location: ../change_email.php?success=ok");
    exit;

} elseif ($action === 'change_password') {
    $oldPassword     = $_POST['old_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        header("Location: ../change_password.php?error=empty");
        exit;
    }
    if ($newPassword !== $confirmPassword) {
        header("Location: ../change_password.php?error=mismatch");
        exit;
    }

    $currentUser = $usersRepo->findUserById($userId); 
    if (!$currentUser) {
        header("Location: ../change_password.php?error=wrong");
        exit;
    }

    if (!password_verify($oldPassword, $currentUser['password'])) {
        header("Location: ../change_password.php?error=wrong");
        exit;
    }

    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
    $usersRepo->updatePassword($userId, $hashed);

    header("Location: ../change_password.php?success=ok");
    exit;
}

header("Location: ../settings.php");
exit;
