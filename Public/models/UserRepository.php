<?php

require_once __DIR__ . '/User.php';

class UserRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findUserByEmail($email)
    {
        $sql = "SELECT id_user, email, password, role 
                FROM users 
                WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['email'], $row['password'], $row['role'], $row['id_user']);
        }
        return null;
    }

    public function saveUser(User $user)
    {
        $sql = "INSERT INTO users (email, password, role) 
                VALUES (:email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':role', $user->getRole(), PDO::PARAM_INT);
        $stmt->execute();
    }
}
