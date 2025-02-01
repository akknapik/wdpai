<?php

class Database
{
    private $host = 'db';
    private $port = '5432';
    private $dbName = 'postgres';
    private $user = 'postgres';
    private $password = 'admin';

    private $conn;

    public function __construct()
    {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbName}";
        try {
            $this->conn = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
