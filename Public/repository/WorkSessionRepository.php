<?php

class WorkSessionRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findActiveSessionByUser($id_user)
    {
        $sql = "SELECT id_session, id_user, time_start, time_end, status
                FROM work_sessions
                WHERE id_user = :id_user
                  AND status = 1
                  AND time_end IS NULL
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createSession($id_user)
    {
        $sql = "INSERT INTO work_sessions (id_user, time_start, status)
                VALUES (:id_user, NOW(), 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function stopActiveSession($id_user)
    {
        $sql = "UPDATE work_sessions
                SET time_end = NOW(), status = 2
                WHERE id_session = (
                  SELECT id_session
                  FROM work_sessions
                  WHERE id_user = :id_user
                    AND status = 1
                    AND time_end IS NULL
                  ORDER BY time_start DESC
                  LIMIT 1
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCurrentSessionWorkTime($userId)
    {
        $sql = "SELECT get_current_session_work_time(:user_id) AS work_minutes";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $minutes = $stmt->fetchColumn(); 
        return (int)$minutes;
    }

    
    public function getDailyWorkTime($userId)
    {
        $sql = "SELECT get_daily_work_time(:user_id) AS work_minutes";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $minutes = $stmt->fetchColumn();
        return (int)$minutes;
    }

    public function getWeeklyWorkTime($userId)
    {
        $sql = "SELECT get_weekly_work_time(:user_id) AS work_minutes";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $minutes = $stmt->fetchColumn();
        return (int)$minutes;
    }

    public function getMonthlyWorkTime($userId)
    {
        $sql = "SELECT get_monthly_work_time(:user_id) AS work_minutes";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $minutes = $stmt->fetchColumn();
        return (int)$minutes;
    }

    public function getYearlyWorkTime($userId)
    {
        $sql = "SELECT get_yearly_work_time(:user_id) AS work_minutes";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $minutes = $stmt->fetchColumn();
        return (int)$minutes;
    }
}
