<?php

require_once '../db/Database.php';
require_once '../models/UserRepository.php';
require_once '../services/AuthorizationService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $repeatPassword = $_POST['repeat_password'] ?? '';

    if (empty($email) || empty($password) || empty($repeatPassword)) {
        header("Location: ../register.php?error=empty_field");
        exit;
    }

    if ($password !== $repeatPassword) {
        header("Location: ../register.php?error=wrong_repeat_password");
        exit;
    }

    $database = new Database();
    $connection = $database->getConnection();
    $userRepo = new UserRepository($connection);
    $authService = new AuthorizationService($userRepo);

    $success = $authService->register($email, $password);
    if ($success) {
        header("Location: ../login.php?message=registered");
    } else {
        header("Location: ../register.php?error=email_exists");
    }
}
