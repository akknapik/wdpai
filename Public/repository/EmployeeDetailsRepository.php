<?php

class EmployeeDetailsRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findUserById($userId)
    {
        $sql = "SELECT id_user, firstname, lastname, email, role_name
                FROM vw_user_info
                WHERE id_user = :uid
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function findLeavesByUser($userId)
    {
        $sql = "
            SELECT 
                l.id_leave,
                t.name AS type_name,
                s.name AS status_name,
                l.date_start,
                l.date_end,
                l.reason,
                l.additional_notes,
                l.manager_info,
                l.status AS status_id,
                l.leave_type AS leave_type_id
            FROM leaves l
            JOIN leave_type t ON l.leave_type = t.id_leave_type
            JOIN leave_status s ON l.status = s.id_status
            WHERE l.id_user = :uid
            ORDER BY l.date_start DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateLeaveStatus($leaveId, $newStatus, $managerInfo)
    {
        $sql = "
        UPDATE leaves
        SET status = :st, manager_info = :mi
        WHERE id_leave = :lid
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':st', $newStatus, PDO::PARAM_INT);
        $stmt->bindValue(':mi', $managerInfo);
        $stmt->bindValue(':lid', $leaveId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
