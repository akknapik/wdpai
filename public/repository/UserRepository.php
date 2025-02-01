<?php

require_once __DIR__ . './../models/User.php';

class UserRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findUserByEmail($email)
    {
        $sql = "SELECT id_user, firstname, lastname, email, password, role 
                FROM users 
                WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['firstname'], $row['lastname'], $row['email'], $row['password'], $row['role'], $row['id_user']);
        }
        return null;
    }

    public function findUserById($userId)
    {
        $sql = "SELECT id_user, email, password, firstname, lastname
                FROM users
                WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveUser(User $user)
    {
        $sql = "INSERT INTO users (firstname, lastname, email, password, role) 
                VALUES (:firstname, :lastname, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':firstname', $user->getFirstname(), PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $user->getLastname(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':role', $user->getRole(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function emailExists($email)
    {
        $sql = "SELECT 1 FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function updateEmail($userId, $newEmail)
    {
        $sql = "UPDATE users
                SET email = :email
                WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePassword($userId, $hashedPassword)
    {
        $sql = "UPDATE users
                SET password = :passwd
                WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':passwd', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
