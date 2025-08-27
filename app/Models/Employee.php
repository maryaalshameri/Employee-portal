<?php
namespace App\Models;
use App\Traits\LoggingTrait; 
use App\Core\App;
use PDO;

class Employee {
     use LoggingTrait;
    public static function all() {
        $stmt = App::db()->prepare("SELECT employees.*, users.name, users.email, users.role
                                    FROM employees
                                    JOIN users ON employees.user_id = users.id 
                                    WHERE employees.deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $stmt = App::db()->prepare("SELECT employees.*, users.name, users.email, users.role
                                    FROM employees
                                    JOIN users ON employees.user_id = users.id
                                    WHERE employees.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {


    $employee = new self();
    
    try {
        $stmt = App::db()->prepare("
            INSERT INTO employees
            (user_id, department, position, hire_date, salary, phone, address, work_type, leaveBalance)
            VALUES (:user_id, :department, :position, :hire_date, :salary, :phone, :address, :work_type, :leaveBalance)
        ");
        $result = $stmt->execute($data);
        
        if ($result) {
            $employee->log("تم إنشاء موظف جديد", [
                'user_id' => $data['user_id'],
                'employee_id' => App::db()->lastInsertId(),
                'department' => $data['department']
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $employee->log("خطأ في إنشاء موظف", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }

        
    }




    public static function update($userId, $data) {



        $employee = new self();
    
    try {
        $stmt = App::db()->prepare("
            UPDATE employees
            SET department = :department,
                position   = :position,
                hire_date  = :hire_date,
                salary     = :salary,
                phone      = :phone,
                address    = :address,
                work_type  = :work_type,
                leaveBalance = :leaveBalance
            WHERE user_id = :user_id
        ");
        $data['user_id'] = $userId;
        $result = $stmt->execute($data);
        
        if ($result) {
            $employee->log("تم تعديل موظف ", [
                'user_id' => $data['user_id'],
               
                'department' => $data['department']
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $employee->log("خطأ في تعديل موظف", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }

    public static function softDelete($id){
  
        $employee = new self();
    
    try {
        $stmt = App::db()->prepare("UPDATE employees SET deleted_at = NOW() WHERE id = :id");
        $data['user_id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $employee->log("تم نقل الموظف الى سلة المهملات ", [
                'user_id' => $data['user_id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $employee->log("خطأ في نقل الموظف الى سلة المهملات", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }

    public static function allTrash(){
        $db = App::db();
        $stmt = $db->prepare("SELECT e.*, u.name, u.email FROM employees e JOIN users u ON e.user_id = u.id WHERE e.deleted_at IS NOT NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function employeeRestore($id){

                $employee = new self();
    
    try {
        $stmt = App::db()->prepare("UPDATE employees SET deleted_at = NULL WHERE id = :id");
        $data['user_id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $employee->log("تم استعادة الموظف من  سلة المهملات ", [
                'user_id' => $data['user_id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $employee->log("خطأ في استعادة الموظف من سلة المهملات", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }


    }

    public static function getBaseSalary($employeeId) {
        $stmt = App::db()->prepare("
            SELECT salary 
            FROM employees 
            WHERE id = :employee_id AND deleted_at IS NULL
        ");
        $stmt->execute(['employee_id' => $employeeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['salary'] ?? 0;
    }

    // دالة للتحقق من وجود مدير في القسم
    public static function hasManagerInDepartment($department) {
        $stmt = App::db()->prepare("
            SELECT COUNT(*) as count 
            FROM employees e 
            JOIN users u ON e.user_id = u.id 
            WHERE e.department = :department 
            AND u.role = 'manager' 
            AND e.deleted_at IS NULL
        ");
        $stmt->execute(['department' => $department]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // دالة للتحقق من وجود اسم مستخدم مكرر
    public static function isNameExists($name, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE name = :name";
        $params = ['name' => $name];
        
        if ($excludeUserId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeUserId;
        }
        
        $stmt = App::db()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }


public static function findByUserId($userId) {
    
    $stmt = App::db()->prepare("
        SELECT e.*, u.name, u.email, u.role, e.id as employee_id
        FROM employees e
        JOIN users u ON e.user_id = u.id
        WHERE u.id = :user_id AND e.deleted_at IS NULL
    ");
    $stmt->execute(['user_id' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // للتحقق من النتيجة
    error_log("Employee data for user $userId: " . print_r($result, true));
    
    return $result;
}

public static function getManagersWithEvaluationsAndTasks()
{
    $db = App::db();
    
    $stmt = $db->prepare("
        SELECT 
            e.id,
            u.name,
            e.department,
            e.position,
            (SELECT COUNT(*) FROM evaluations WHERE employee_id = e.id AND deleted_at IS NULL) as evaluation_count,
            (SELECT COALESCE(AVG((performance_score + quality_score + punctuality_score + teamwork_score) / 4), 0) 
             FROM evaluations WHERE employee_id = e.id AND deleted_at IS NULL) as avg_evaluation_score,
            (SELECT COUNT(*) FROM tasks WHERE assigned_to = e.id) as task_count
        FROM employees e
        JOIN users u ON e.user_id = u.id
        WHERE u.role = 'manager' AND e.deleted_at IS NULL
        ORDER BY u.name
    ");
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public static function updateProfile($employeeId, $data)
{
    $stmt = App::db()->prepare("
        UPDATE employees 
        SET phone = :phone, 
            address = :address,
            updated_at = NOW()
        WHERE id = :id
    ");
    
    return $stmt->execute([
        'phone' => $data['phone'],
        'address' => $data['address'],
        'id' => $employeeId
    ]);
}
}