<?php

require_once '../db/Database.php';
require_once '../repository/UserRepository.php';
require_once '../services/AuthorizationService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../login.php?error=empty_field");
        exit;
    }

    $database = new Database();
    $connection = $database->getConnection();
    $userRepo = new UserRepository($connection);
    $authService = new AuthorizationService($userRepo);

    $loginSuccess = $authService->login($email, $password);
    if ($loginSuccess) {
        header("Location: ../index.php");
    } else {
        header("Location: ../login.php?error=invalid_data");
    }
}
