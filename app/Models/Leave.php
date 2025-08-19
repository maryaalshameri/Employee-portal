<?php
namespace app\Models;

use app\core\Database;

class Leave {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($employeeId, $startDate, $endDate, $type, $reason) {
        $stmt = $this->db->prepare("
            INSERT INTO leaves (employee_id, start_date, end_date, type, reason)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$employeeId, $startDate, $endDate, $type, $reason]);
    }
    
    public function getByEmployee($employeeId) {
        $stmt = $this->db->prepare("
            SELECT * FROM leaves 
            WHERE employee_id = ? 
            ORDER BY start_date DESC
        ");
        $stmt->execute([$employeeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT l.*, e.user_id, u.name as employee_name
            FROM leaves l
            JOIN employees e ON l.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            ORDER BY l.start_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($leaveId, $status) {
        $stmt = $this->db->prepare("UPDATE leaves SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $leaveId]);
    }
}