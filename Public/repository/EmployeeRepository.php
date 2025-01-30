<?php

class EmployeeRepository
{
    private $db;

    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }
    
    public function findAllForAdmin($adminId)
    {
        $sql = "
          SELECT id_user, firstname, lastname, role, pending_leaves
          FROM vw_employees
          ORDER BY 
            (CASE WHEN id_user = :adminId THEN 0 ELSE 1 END),
            lastname ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':adminId', $adminId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
