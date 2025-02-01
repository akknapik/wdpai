<?php

class LeaveTypeRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findAllTypes()
    {
        $sql = "SELECT id_leave_type, name FROM leave_type ORDER BY name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
