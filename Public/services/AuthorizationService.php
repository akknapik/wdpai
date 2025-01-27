<?php

require_once __DIR__ . '/../models/UserRepository.php';

class AuthorizationService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

 
    public function register($firstname, $lastname, $email, $password)
    {
        $existingUser = $this->userRepository->findUserByEmail($email);
        if ($existingUser !== null) {
            return false; 
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $user = new User($firstname, $lastname, $email, $hashedPassword);
        $this->userRepository->saveUser($user);

        return true;
    }


    public function login($email, $password)
    {
        $user = $this->userRepository->findUserByEmail($email);

        if ($user === null) {
            return false; 
        }

        if (password_verify($password, $user->getPassword())) {
            session_start();
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['firstname'] = $user->getFirstname();
            $_SESSION['lastname'] = $user->getLastname();
            return true;
        }

        return false; 
    }


    public static function checkLoggedIn()
    {
        session_start();
        return isset($_SESSION['user_id']);
    }


    public static function logout()
    {
        session_start();
        session_destroy();
    }
}
