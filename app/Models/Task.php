<?php
namespace App\Models;

use App\Core\App;
use PDO;
use App\Traits\LoggingTrait; 
class Task {
     use LoggingTrait;

    public static function create($data)
{
    $db = App::db();
    
    $sql = "INSERT INTO tasks 
            (title, description, assigned_to, due_date, priority, status, created_by)
            VALUES 
            (:title, :description, :assigned_to, :due_date, :priority, :status, :created_by)";
    
    $stmt = $db->prepare($sql);
    
    return $stmt->execute([
        ':title' => $data['title'],
        ':description' => $data['description'] ?? '',
        ':assigned_to' => $data['assigned_to'],
        ':due_date' => $data['due_date'] ?? null,
        ':priority' => $data['priority'] ?? 'medium',
        ':status' => $data['status'] ?? 'todo',
        ':created_by' => $data['created_by'] ?? null
    ]);
}

public static function updateStatus($taskId, $status)
{
    $db = App::db();
    
    $stmt = $db->prepare("
        UPDATE tasks 
        SET status = :status, updated_at = NOW()
        WHERE id = :id
    ");
    
    return $stmt->execute([
        ':status' => $status,
        ':id' => $taskId
    ]);
}

public static function find($taskId)
{
    $db = App::db();
    
    $stmt = $db->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $taskId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

public static function getEmployeeTasks($employeeId)
{
    $db = App::db();
    
    $stmt = $db->prepare("
        SELECT t.*, u.name as created_by_name
        FROM tasks t
        LEFT JOIN users u ON t.created_by = u.id
        WHERE t.assigned_to = :employee_id 
        ORDER BY t.due_date ASC, t.priority DESC
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function getRecentEmployeeTasks($employeeId, $limit = 5)
{
    $db = App::db();
    
    $stmt = $db->prepare("
        SELECT t.*, u.name as created_by_name
        FROM tasks t
        LEFT JOIN users u ON t.created_by = u.id
        WHERE t.assigned_to = :employee_id 
        ORDER BY t.created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':employee_id', $employeeId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}