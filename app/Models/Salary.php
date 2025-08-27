<?php
namespace App\Models;

use App\Core\App;
use PDO;
use App\Traits\LoggingTrait; 

class Salary {
     use LoggingTrait;
    public static function all() {
        $stmt = App::db()->prepare("
            SELECT s.*, e.id as employee_id, u.name as employee_name 
            FROM salaries s 
            JOIN employees e ON s.employee_id = e.id 
            JOIN users u ON e.user_id = u.id 
            WHERE s.deleted_at IS NULL
            ORDER BY s.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function pending() {
        $stmt = App::db()->prepare("
            SELECT s.*, e.id as employee_id, u.name as employee_name 
            FROM salaries s 
            JOIN employees e ON s.employee_id = e.id 
            JOIN users u ON e.user_id = u.id 
            WHERE s.status = 'pending' AND s.deleted_at IS NULL
            ORDER BY s.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $stmt = App::db()->prepare("
               SELECT s.*, 
               e.id as employee_id, 
               u.name as employee_name, 
               u.email as employee_email,
               approver.name as approved_by_name
        FROM salaries s 
        JOIN employees e ON s.employee_id = e.id 
        JOIN users u ON e.user_id = u.id 
        LEFT JOIN users approver ON s.approved_by = approver.id
        WHERE s.id = :id
    
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {

        $salary = new self();
    
    try {
        $stmt = App::db()->prepare("
            INSERT INTO salaries 
            (employee_id, amount, bonusPercentage, deductionPercentage, payment_date,comments, status) 
            VALUES (:employee_id, :amount, :bonusPercentage, :deductionPercentage, :payment_date, :comments, 'pending')
        ");
        $result = $stmt->execute($data);
        
        if ($result) {
            $salary->log("تم إنشاء طلب راتب جديد", [
                'employee_id' => $data['employee_id'],
                'amount' => $data['amount'],
                'bonusPercentage'=> $data['bonusPercentage'],
                'deductionPercentage' =>$data['deductionPercentage'],
                'salary_id' => App::db()->lastInsertId()
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $salary->log("خطأ في إنشاء طلب الراتب", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }

    public static function approve($id, $approved_by, $comments = null) {


       $salary = new self();
    
     try {
        $stmt = App::db()->prepare("
            UPDATE salaries 
            SET status = 'approved', approved_by = :approved_by, comments = :comments, updated_at = NOW() 
            WHERE id = :id
        ");
        $data['id'] = $id;
        $data['approved_by'] = $approved_by;
        $data['comments'] = $comments;

        $result = $stmt->execute([
            'id' => $id,
            'approved_by' => $approved_by,
            'comments' => $comments
        ]);
        
        if ($result) {
            $salary->log("تم الموافقة  على الراتب ", [
                'id' => $data['id'],
                'approved_by'=>$data['approved_by'],
                'comments'=>$data['comments'],
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $salary->log("خطأ الوافقة على الراتب", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }

    public static function reject($id, $approved_by, $comments = null) {

             $salary = new self();
    
    try {
        $stmt = App::db()->prepare("
            UPDATE salaries 
            SET status = 'rejected', approved_by = :approved_by, comments = :comments, updated_at = NOW() 
            WHERE id = :id
        ");
        $data['id'] = $id;
        $data['approved_by'] = $approved_by;
        $data['comments'] = $comments;
        $result = $stmt->execute([
            'id' => $id,
            'approved_by' => $approved_by,
            'comments' => $comments
        ]);
        
        if ($result) {
            $salary->log("تم الرفض  على الراتب ", [
                'id' => $data['id'],
                'approved_by'=>$data['approved_by'],
                'comments'=>$data['comments'],
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $salary->log("خطأ في الرفض على الراتب", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }

    public static function softDelete($id) {
        // $stmt = App::db()->prepare("UPDATE salaries SET deleted_at = NOW() WHERE id = :id");
        // return $stmt->execute(['id' => $id]);
                 $salary = new self();
    
    try {
        $stmt = App::db()->prepare("UPDATE salaries SET deleted_at = NOW() WHERE id = :id");
        $data['id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $salary->log("تم نقل الاجازه الى سلة المهملات ", [
                'id' => $data['id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $salary->log("خطأ في نقل الراتب الى سلة المهملات", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    
    }

    public static function forceDelete($id) {
    // $stmt = App::db()->prepare("DELETE FROM salaries WHERE id = :id");
    // return $stmt->execute(['id' => $id]);                  
      $salary = new self();
    
    try {
        $stmt = App::db()->prepare("DELETE FROM salaries WHERE id = :id");
        $data['id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $salary->log("تم حذف  الراتب نهائيا ", [
                'id' => $data['id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $salary->log("خطأ في  حذف  الراتب نهائيا ", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }


}


   public static function allTrash() {
    $stmt = App::db()->prepare("
        SELECT 
            s.*, 
            e.id as employee_id, 
            u.name as employee_name,
            (s.amount + (s.amount * s.bonusPercentage / 100) - (s.amount * s.deductionPercentage / 100)) as net_salary
        FROM salaries s 
        JOIN employees e ON s.employee_id = e.id 
        JOIN users u ON e.user_id = u.id 
        WHERE s.deleted_at IS NOT NULL
        ORDER BY s.deleted_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public static function restore($id) {
        // $stmt = App::db()->prepare("UPDATE salaries SET deleted_at = NULL WHERE id = :id");
        // return $stmt->execute(['id' => $id]);
                            $salary = new self();
    
    try {
        $stmt = App::db()->prepare("UPDATE salaries SET deleted_at = NULL WHERE id = :id");
        $data['id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $salary->log("تم استعادة الراتب من سلة المهملات ", [
                'id' => $data['id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $salary->log("خطأ في استعادة الراتب من سلة المهملات", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }

    public static function hasPendingSalaries() {
        $stmt = App::db()->prepare("SELECT COUNT(*) as count FROM salaries WHERE status = 'pending' AND deleted_at IS NULL");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public static function getStatistics() {
        $stmt = App::db()->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(amount + (amount * bonusPercentage / 100) - (amount * deductionPercentage / 100)) as total_amount
            FROM salaries 
            WHERE deleted_at IS NULL
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getApprovedByUser($userId) {
        $stmt = App::db()->prepare("
            SELECT s.*, e.id as employee_id, u.name as employee_name 
            FROM salaries s 
            JOIN employees e ON s.employee_id = e.id 
            JOIN users u ON e.user_id = u.id 
            WHERE s.approved_by = :user_id AND s.deleted_at IS NULL 
            AND s.status = 'approved'
            ORDER BY s.updated_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // في ملف Salary.php
public static function getEmployeeSalaries($employeeId) {
    $stmt = App::db()->prepare("
        SELECT s.*, u2.name as approved_by_name
        FROM salaries s
        LEFT JOIN users u2 ON s.approved_by = u2.id
        WHERE s.employee_id = :employee_id AND s.deleted_at IS NULL
        ORDER BY s.created_at DESC
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // للتحقق
    error_log("Employee salaries for $employeeId: " . print_r($result, true));
    
    return $result;
}

public static function getLastEmployeeSalary($employeeId) {
    $stmt = App::db()->prepare("
        SELECT s.*
        FROM salaries s
        WHERE s.employee_id = :employee_id 
        AND s.status = 'approved' 
        AND s.deleted_at IS NULL
        ORDER BY s.payment_date DESC
        LIMIT 1
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // للتحقق
    error_log("Last salary for $employeeId: " . print_r($result, true));
    
    return $result;
}

    public static function getLastEmployeeSalaryRequest($employeeId) {
    $stmt = App::db()->prepare("
        SELECT s.*
        FROM salaries s
        WHERE s.employee_id = :employee_id 
        AND s.deleted_at IS NULL
        ORDER BY s.created_at DESC
        LIMIT 1
    ");
    $stmt->execute(['employee_id' => $employeeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createEmployeeRequest($employeeId) {
    $employee = Employee::find($employeeId);

    $stmt = App::db()->prepare("
        INSERT INTO salaries 
        (employee_id, amount, bonusPercentage, deductionPercentage, payment_date, status) 
        VALUES (:employee_id, :amount, 0, 0, :payment_date, 'pending')
    ");

    return $stmt->execute([
        'employee_id' => $employeeId,
        'amount' => $employee['salary'],
        'payment_date' => date('Y-m-d')
    ]);
    }


    public static function getRecentEmployeeSalaries($employeeId, $limit = 5)
{
    $stmt = App::db()->prepare("
        SELECT s.*, u2.name as approved_by_name
        FROM salaries s
        LEFT JOIN users u2 ON s.approved_by = u2.id
        WHERE s.employee_id = :employee_id 
        AND s.deleted_at IS NULL
        ORDER BY s.created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':employee_id', $employeeId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}