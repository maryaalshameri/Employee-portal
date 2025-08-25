<?php
namespace App\Models;
use App\Traits\LoggingTrait; 
use App\Core\App;
use PDO;

class Evaluation {
     use LoggingTrait;
public static function create($data) {
    try {
        $db = App::db();
        
        $sql = "INSERT INTO evaluations 
                (employee_id, evaluator_id, performance_score, quality_score, 
                 punctuality_score, teamwork_score, comments, evaluation_date, next_evaluation_date)
                VALUES 
                (:employee_id, :evaluator_id, :performance_score, :quality_score, 
                 :punctuality_score, :teamwork_score, :comments, :evaluation_date, :next_evaluation_date)";
        
        $stmt = $db->prepare($sql);
        
        $params = [
            ':employee_id' => $data['employee_id'],
            ':evaluator_id' => $data['evaluator_id'],
            ':performance_score' => $data['performance_score'],
            ':quality_score' => $data['quality_score'],
            ':punctuality_score' => $data['punctuality_score'],
            ':teamwork_score' => $data['teamwork_score'],
            ':comments' => $data['comments'] ?? '',
            ':evaluation_date' => $data['evaluation_date'],
            ':next_evaluation_date' => $data['next_evaluation_date'] ?? null
        ];
        
        $result = $stmt->execute($params);
        
        if ($result) {
            error_log("Evaluation created successfully for employee ID: " . $data['employee_id']);
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . print_r($errorInfo, true));
            return false;
        }
        
    } catch (\PDOException $e) {
        error_log("PDO Exception: " . $e->getMessage());
        return false;
    }
}
    
    public static function find($id) {
        $stmt = App::db()->prepare("
            SELECT e.*, 
                   u.name as employee_name, 
                   eval.name as evaluator_name,
                   emp.department, emp.position
            FROM evaluations e
            JOIN employees emp ON e.employee_id = emp.id
            JOIN users u ON emp.user_id = u.id
            JOIN users eval ON e.evaluator_id = eval.id
            WHERE e.id = :id AND e.deleted_at IS NULL
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // public static function getEmployeeEvaluations($employeeId) {
    //     $stmt = App::db()->prepare("
    //         SELECT e.*, eval.name as evaluator_name
    //         FROM evaluations e
    //         JOIN users eval ON e.evaluator_id = eval.id
    //         WHERE e.employee_id = :employee_id AND e.deleted_at IS NULL
    //         ORDER BY e.evaluation_date DESC
    //     ");
    //     $stmt->execute(['employee_id' => $employeeId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    
    public static function getDepartmentEvaluations($department) {
        $stmt = App::db()->prepare("
            SELECT ev.*, 
                   u.name as employee_name, 
                   eval.name as evaluator_name,
                   emp.position, emp.department
            FROM evaluations ev
            JOIN employees emp ON ev.employee_id = emp.id
            JOIN users u ON emp.user_id = u.id
            JOIN users eval ON ev.evaluator_id = eval.id
            WHERE emp.department = :department AND ev.deleted_at IS NULL
            ORDER BY ev.evaluation_date DESC
        ");
        $stmt->execute(['department' => $department]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // public static function getAverageScores($employeeId) {
    //     $stmt = App::db()->prepare("
    //         SELECT 
    //             AVG(performance_score) as avg_performance,
    //             AVG(quality_score) as avg_quality,
    //             AVG(punctuality_score) as avg_punctuality,
    //             AVG(teamwork_score) as avg_teamwork,
    //             COUNT(*) as total_evaluations
    //         FROM evaluations 
    //         WHERE employee_id = :employee_id AND deleted_at IS NULL
    //     ");
    //     $stmt->execute(['employee_id' => $employeeId]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
    
    public static function update($id, $data) {
        $stmt = App::db()->prepare("
            UPDATE evaluations 
            SET performance_score = :performance_score,
                quality_score = :quality_score,
                punctuality_score = :punctuality_score,
                teamwork_score = :teamwork_score,
                comments = :comments,
                evaluation_date = :evaluation_date,
                next_evaluation_date = :next_evaluation_date,
                updated_at = NOW()
            WHERE id = :id
        ");
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    
    public static function softDelete($id) {
        $stmt = App::db()->prepare("
            UPDATE evaluations 
            SET deleted_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }
    
    public static function getRecentEvaluations($department, $limit = 5) {
        $stmt = App::db()->prepare("
            SELECT ev.*, 
                   u.name as employee_name, 
                   eval.name as evaluator_name
            FROM evaluations ev
            JOIN employees emp ON ev.employee_id = emp.id
            JOIN users u ON emp.user_id = u.id
            JOIN users eval ON ev.evaluator_id = eval.id
            WHERE emp.department = :department AND ev.deleted_at IS NULL
            ORDER BY ev.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':department', $department, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
public static function hasRecentEvaluation($employeeId, $months = 3) {
    try {
        $db = App::db();
        
        $stmt = $db->prepare("
            SELECT COUNT(*) as count 
            FROM evaluations 
            WHERE employee_id = :employee_id 
            AND evaluation_date >= DATE_SUB(NOW(), INTERVAL :months MONTH)
            AND deleted_at IS NULL
        ");
        
        $stmt->execute([
            'employee_id' => $employeeId,
            'months' => $months
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
        
    } catch (\PDOException $e) {
        error_log("Error checking recent evaluation: " . $e->getMessage());
        return false;
    }
}
    // في ملف Evaluation.php
public static function getAverageScore($employeeId) {
    $stmt = App::db()->prepare("
        SELECT 
            AVG(performance_score) as avg_performance,
            AVG(quality_score) as avg_quality,
            AVG(punctuality_score) as avg_punctuality,
            AVG(teamwork_score) as avg_teamwork,
            AVG((performance_score + quality_score + punctuality_score + teamwork_score) / 4) as overall_avg
        FROM evaluations 
        WHERE employee_id = :employee_id AND deleted_at IS NULL
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// public static function getEmployeeEvaluations($employeeId)
// {
//     $db = App::db();
    
//     $stmt = $db->prepare("
//         SELECT e.*, u.name as evaluator_name
//         FROM evaluations e
//         JOIN users u ON e.evaluator_id = u.id
//         WHERE e.employee_id = :employee_id AND e.deleted_at IS NULL
//         ORDER BY e.evaluation_date DESC
//     ");
//     $stmt->execute(['employee_id' => $employeeId]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// public static function getAverageScores($employeeId)
// {
//     $db = App::db();
    
//     $stmt = $db->prepare("
//         SELECT 
//             AVG(performance_score) as avg_performance,
//             AVG(quality_score) as avg_quality,
//             AVG(punctuality_score) as avg_punctuality,
//             AVG(teamwork_score) as avg_teamwork,
//             COUNT(*) as total_evaluations
//         FROM evaluations 
//         WHERE employee_id = :employee_id AND deleted_at IS NULL
//     ");
//     $stmt->execute(['employee_id' => $employeeId]);
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// } 
public static function getRecentEmployeeEvaluations($employeeId, $limit = 5)
{
    $db = App::db();
    
    $stmt = $db->prepare("
        SELECT e.*, u.name as evaluator_name
        FROM evaluations e
        JOIN users u ON e.evaluator_id = u.id
        WHERE e.employee_id = :employee_id 
        AND e.deleted_at IS NULL
        ORDER BY e.evaluation_date DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':employee_id', $employeeId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function getEmployeeEvaluations($employeeId)
{
    $db = App::db();
    
    $stmt = $db->prepare("
        SELECT e.*, u.name as evaluator_name
        FROM evaluations e
        JOIN users u ON e.evaluator_id = u.id
        WHERE e.employee_id = :employee_id AND e.deleted_at IS NULL
        ORDER BY e.evaluation_date DESC
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function getAverageScores($employeeId)
 {
    $db = App::db();
    
    $stmt = $db->prepare("
        SELECT 
            AVG(performance_score) as avg_performance,
            AVG(quality_score) as avg_quality,
            AVG(punctuality_score) as avg_punctuality,
            AVG(teamwork_score) as avg_teamwork,
            COUNT(*) as total_evaluations,
            AVG((performance_score + quality_score + punctuality_score + teamwork_score) / 4) as overall_avg
        FROM evaluations 
        WHERE employee_id = :employee_id AND deleted_at IS NULL
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
 }
}