<?php

class LeaveRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findAllByUser($userId)
    {
        $sql = "SELECT id_leave, leave_type, date_start, date_end,
                       reason, additional_notes, status, manager_info
                FROM leaves
                WHERE id_user = :user_id
                ORDER BY date_start DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createLeave($idUser, $leaveType, $dateStart, $dateEnd, $reason, $additionalNotes)
    {
        $sql = "INSERT INTO leaves (id_user, leave_type, date_start, date_end, reason, additional_notes)
                VALUES (:id_user, :leave_type, :date_start, :date_end, :reason, :additional_notes)
                RETURNING id_leave";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->bindValue(':leave_type', $leaveType, PDO::PARAM_INT);
        $stmt->bindValue(':date_start', $dateStart); 
        $stmt->bindValue(':date_end', $dateEnd);
        $stmt->bindValue(':reason', $reason);
        $stmt->bindValue(':additional_notes', $additionalNotes);
        $stmt->execute();

        $newId = $stmt->fetchColumn();  
        return $newId;
    }
}
