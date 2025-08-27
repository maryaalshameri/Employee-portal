<?php
namespace App\Models;
use App\Traits\LoggingTrait; 
use App\Core\App;
use PDO;

class Leave {
     use LoggingTrait;
    public static function all() {
        $stmt = App::db()->prepare("
            SELECT l.*, e.id as employee_id, u.name as employee_name, 
                   u2.name as approved_by_name
            FROM leaves l
            JOIN employees e ON l.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN users u2 ON l.approved_by = u2.id
            WHERE l.deleted_at IS NULL
            ORDER BY l.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function find($id) {
        $stmt = App::db()->prepare("
            SELECT l.*, e.id as employee_id, u.name as employee_name, 
                   u.email as employee_email, u2.name as approved_by_name
            FROM leaves l
            JOIN employees e ON l.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN users u2 ON l.approved_by = u2.id
            WHERE l.id = :id AND l.deleted_at IS NULL
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function pending() {
        $stmt = App::db()->prepare("
            SELECT l.*, e.id as employee_id, u.name as employee_name,
                   u2.name as approved_by_name
            FROM leaves l
            JOIN employees e ON l.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN users u2 ON l.approved_by = u2.id
            WHERE l.status = 'pending' AND l.deleted_at IS NULL
            ORDER BY l.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function create($data)
    {
        // $db = App::db();
        
        // $sql = "INSERT INTO leaves 
        //         (employee_id, start_date, end_date, days_requested, type, reason, status)
        //         VALUES 
        //         (:employee_id, :start_date, :end_date, :days_requested, :type, :reason, :status)";
        
        // $stmt = $db->prepare($sql);
        
        // return $stmt->execute([
        //     ':employee_id' => $data['employee_id'],
        //     ':start_date' => $data['start_date'],
        //     ':end_date' => $data['end_date'],
        //     ':days_requested' => $data['days_requested'],
        //     ':type' => $data['type'],
        //     ':reason' => $data['reason'],
        //     ':status' => $data['status']
        // ]);

            $leavs = new self();
    
    try {
        $db = App::db();
         $sql = "INSERT INTO leaves 
                (employee_id, start_date, end_date, days_requested, type, reason, status)
                VALUES 
                (:employee_id, :start_date, :end_date, :days_requested, :type, :reason, :status)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':employee_id' => $data['employee_id'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':days_requested' => $data['days_requested'],
            ':type' => $data['type'],
            ':reason' => $data['reason'],
            ':status' => $data['status']
        ]);
        
        if ($result) {
            $leavs->log("تم طلب إجازه جديدة", [
                'type' => $data['type'],
                'days_requested' => $data['days_requested'],
                'leave_id' => $db->lastInsertId()
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $leavs->log("خطأ في إنشاء الاجازه", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }
    
   public static function approve($id, $approved_by, $comments = null) {
    // $db = App::db();

    // try {
    //     $db->beginTransaction();

    //     // 1. الحصول على بيانات الإجازة
    //     $stmt = $db->prepare("SELECT * FROM leaves WHERE id = :id");
    //     $stmt->execute(['id' => $id]);
    //     $leave = $stmt->fetch(\PDO::FETCH_ASSOC);

    //     if (!$leave) {
    //         throw new \Exception("طلب الإجازة غير موجود");
    //     }

    //     // 2. حساب عدد الأيام
    //     $daysRequested = (new \DateTime($leave['end_date']))
    //         ->diff(new \DateTime($leave['start_date']))
    //         ->days + 1;

    //     // 3. الحصول على بيانات الموظف
    //     $stmt = $db->prepare("SELECT * FROM employees WHERE id = :emp_id");
    //     $stmt->execute(['emp_id' => $leave['employee_id']]);
    //     $employee = $stmt->fetch(\PDO::FETCH_ASSOC);

    //     if (!$employee) {
    //         throw new \Exception("الموظف غير موجود");
    //     }

    //     // 4. التحقق من الرصيد
    //     if ($employee['leaveBalance'] < $daysRequested) {
    //         throw new \Exception("رصيد الإجازات غير كافٍ");
    //     }

    //     // 5. تحديث رصيد الموظف
    //     $newBalance = $employee['leaveBalance'] - $daysRequested;
    //     $stmt = $db->prepare("UPDATE employees SET leaveBalance = :balance WHERE id = :id");
    //     $stmt->execute([
    //         'balance' => $newBalance,
    //         'id' => $employee['id']
    //     ]);

    //     // 6. تحديث حالة الإجازة
    //     $stmt = $db->prepare("
    //         UPDATE leaves 
    //         SET status = 'approved', 
    //             approved_by = :approved_by, 
    //             comments = :comments,
    //             updated_at = NOW()
    //         WHERE id = :id
    //     ");
    //     $stmt->execute([
    //         'id' => $id,
    //         'approved_by' => $approved_by,
    //         'comments' => $comments
    //     ]);

    //     $db->commit();
    //     return true;

    // } catch (\Throwable $e) {
    //     if ($db->inTransaction()) $db->rollBack();
    //     throw $e;
    // }
    $leave = new self(); // إنشاء instance
    
    try {
        $db = App::db();
        $db->beginTransaction();

        // 1. الحصول على بيانات الإجازة
        $stmt = $db->prepare("SELECT * FROM leaves WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $leaveData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$leaveData) {
            $leave->log("طلب الإجازة غير موجود", ['leave_id' => $id]);
            throw new \Exception("طلب الإجازة غير موجود");
        }

        // 2. حساب عدد الأيام
        $daysRequested = (new \DateTime($leaveData['end_date']))
            ->diff(new \DateTime($leaveData['start_date']))
            ->days + 1;

        // 3. تحديث رصيد الموظف
        $newBalance = $employee['leaveBalance'] - $daysRequested;
        $stmt = $db->prepare("UPDATE employees SET leaveBalance = :balance WHERE id = :id");
        $stmt->execute([
            'balance' => $newBalance,
            'id' => $employee['id']
        ]);

        // 6. تحديث حالة الإجازة
        $stmt = $db->prepare("
            UPDATE leaves 
            SET status = 'approved', 
                approved_by = :approved_by, 
                comments = :comments,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'approved_by' => $approved_by,
            'comments' => $comments
        ]);


        $db->commit();
        
        $leave->log("تم الموافقة على الإجازة", [
            'leave_id' => $id,
            'approved_by' => $approved_by,
            'days_requested' => $daysRequested,
            'new_balance' => $newBalance
        ]);
        
        return true;

    } catch (\Throwable $e) {
        if ($db->inTransaction()) $db->rollBack();
        $leave->log("خطأ في الموافقة على الإجازة", [
            'leave_id' => $id,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}

    
    public static function reject($id, $rejected_by, $comments = null) {


     $leave = new self();
    
    try {
        $stmt = App::db()->prepare("
            UPDATE leaves 
            SET status = 'rejected', 
                approved_by = :rejected_by, 
                comments = :comments,
                updated_at = NOW()
            WHERE id = :id
        ");
        $data['id'] = $id;
        $data['rejected_by'] = $rejected_by;
        $data['comments'] = $comments;
        $result = $stmt->execute([
            'id' => $id,
            'rejected_by' => $rejected_by,
            'comments' => $comments
        ]);
        
        if ($result) {
            $leave->log("تم الرفض  على الاجازه ", [
                'id' => $data['id'],
                'rejected_by'=>$data['rejected_by'],
                'comments'=>$data['comments'],
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $leave->log("خطأ في الرفض على الاجازه", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }
    
    public static function update($id, $data) {
        // حساب عدد الأيام تلقائياً
        $start = new \DateTime($data['start_date']);
        $end = new \DateTime($data['end_date']);
        $interval = $start->diff($end);
        $days = $interval->days + 1;
        
        $data['id'] = $id;
        $stmt = App::db()->prepare("
            UPDATE leaves 
            SET start_date = :start_date,
                end_date = :end_date,
                days_requested = :days_requested,
                type = :type,
                reason = :reason,
                updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days_requested' => $days,
            'type' => $data['type'],
            'reason' => $data['reason'],
            'id' => $id
        ]);
    }
    
    public static function softDelete($id) {
    
               $leave = new self();
    
    try {
        $stmt = App::db()->prepare("
            UPDATE leaves 
            SET deleted_at = NOW() 
            WHERE id = :id
        ");
        $data['id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $leave->log("تم نقل الاجازة الى سلة المهملات ", [
                'id' => $data['id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $leave->log("خطأ في نقل الاجازه الى سلة المهملات", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }
    
    public static function restore($id) {
        // $stmt = App::db()->prepare("
        //     UPDATE leaves 
        //     SET deleted_at = NULL 
        //     WHERE id = :id
        // ");
        // return $stmt->execute(['id' => $id]);
      $leave = new self();
    
    try {
        $stmt = App::db()->prepare("
            UPDATE leaves 
            SET deleted_at = NULL 
            WHERE id = :id
        ");
        $data['id'] = $id;
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $leave->log("تم استعادة الاجازة من سلة المهملات ", [
                'id' => $data['id']
               
            ]);
            return true;
        }
        
    } catch (\Exception $e) {
        $leave->log("خطأ في استعادة الاجازه من سلة المهملات", [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        throw $e;
    }
    }
    
    public static function allTrash() {
        $stmt = App::db()->prepare("
            SELECT l.*, e.id as employee_id, u.name as employee_name
            FROM leaves l
            JOIN employees e ON l.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            WHERE l.deleted_at IS NOT NULL
            ORDER BY l.deleted_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // دالة مساعدة للتحقق من وجود إجازات
    public static function hasPendingLeaves() {
        $stmt = App::db()->prepare("
            SELECT COUNT(*) as count 
            FROM leaves 
            WHERE status = 'pending' AND deleted_at IS NULL
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }





// في ملف Leave.php
    public static function getEmployeeLeaves($employeeId) {
        $stmt = App::db()->prepare("
            SELECT l.*, u2.name as approved_by_name
            FROM leaves l
            LEFT JOIN users u2 ON l.approved_by = u2.id
            WHERE l.employee_id = :employee_id AND l.deleted_at IS NULL
            ORDER BY l.created_at DESC
        ");
        $stmt->execute(['employee_id' => $employeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPendingEmployeeLeaves($employeeId) {
        $stmt = App::db()->prepare("
            SELECT l.*
            FROM leaves l
            WHERE l.employee_id = :employee_id 
            AND l.status = 'pending' 
            AND l.deleted_at IS NULL
            ORDER BY l.created_at DESC
        ");
        $stmt->execute(['employee_id' => $employeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }







}



