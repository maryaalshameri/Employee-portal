<?php
namespace app\Models;

use app\core\Database;

class Salary {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($employeeId, $amount, $paymentDate, $notes = null) {
        $stmt = $this->db->prepare("INSERT INTO salaries (employee_id, amount, payment_date, notes) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$employeeId, $amount, $paymentDate, $notes]);
    }
    
    public function getByEmployee($employeeId) {
        $stmt = $this->db->prepare("SELECT * FROM salaries WHERE employee_id = ? ORDER BY payment_date DESC");
        $stmt->execute([$employeeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT s.*, e.user_id, u.name as employee_name
            FROM salaries s
            JOIN employees e ON s.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            ORDER BY s.payment_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}