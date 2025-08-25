<?php
namespace App\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\Task;
use App\Models\Evaluation; // أضف هذا الاستيراد
use App\Core\Auth;
use DateTime;

class ManagerDashboardController extends BaseController
{    
    private function getDepartmentEvaluations($department)
    {
        return Evaluation::getRecentEvaluations($department, 5);
    }

public function dashboard()
{
    Auth::check();
    
    // التحقق من أن المستخدم مدير
    if ($_SESSION['user']['role'] !== 'manager') {
        header("Location: /employee-portal/public/".$_SESSION['user']['role']);
        exit;
    }
    
    // الحصول على بيانات المدير
    $manager = Employee::findByUserId($_SESSION['user']['id']);
    
    if (!$manager) {
        $_SESSION['error'] = "لم يتم العثور على بيانات المدير";
        header("Location: /employee-portal/public/login");
        exit;
    }
    
    // الحصول على الموظفين في قسم المدير
    $employees = $this->getDepartmentEmployees($manager['department']);
    
    // الحصول على إجازات قسم المدير
    $leaves = $this->getDepartmentLeaves($manager['department']);
    
    // الحصول على رواتب قسم المدير
    $salaries = $this->getDepartmentSalaries($manager['department']);
    
    // الحصول على مهام قسم المدير - هذا هو المهم
    $tasks = $this->getDepartmentTasks($manager['department']);
    
    // الحصول على تقييمات قسم المدير
    $evaluations = $this->getDepartmentEvaluations($manager['department']);
    
    // الإحصائيات
    $stats = [
        'total_employees' => count($employees),
        'pending_leaves' => count(array_filter($leaves, function($leave) {
            return $leave['status'] === 'pending';
        })),
        'pending_salaries' => count(array_filter($salaries, function($salary) {
            return $salary['status'] === 'pending';
        })),
        'active_tasks' => count(array_filter($tasks, function($task) {
            return in_array($task['status'], ['todo', 'in_progress']);
        }))
    ];

    $this->render('manager/dashboard.php', [
        'manager' => $manager,
        'employees' => $employees,
        'leaves' => $leaves,
        'salaries' => $salaries,
        'tasks' => $tasks, // تم تمرير المهام إلى العرض
        'evaluations' => $evaluations,
        'stats' => $stats
    ], 'manager/dashboard-layout.php');
}
    
    private function getDepartmentEmployees($department)
    {
        $db = \App\Core\App::db();
        
        $stmt = $db->prepare("
            SELECT e.*, u.name, u.email, u.role
            FROM employees e
            JOIN users u ON e.user_id = u.id
            WHERE e.department = :department 
            AND e.deleted_at IS NULL
            AND u.role = 'employee'
            ORDER BY u.name
        ");
        $stmt->execute(['department' => $department]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    // private function getDepartmentLeaves($department)
    // {
    //     $db = \App\Core\App::db();
        
    //     $stmt = $db->prepare("
    //         SELECT l.*, e.id as employee_id, u.name as employee_name
    //         FROM leaves l
    //         JOIN employees e ON l.employee_id = e.id
    //         JOIN users u ON e.user_id = u.id
    //         WHERE e.department = :department 
    //         AND l.deleted_at IS NULL
    //         ORDER BY l.created_at DESC
    //         LIMIT 10
    //     ");
    //     $stmt->execute(['department' => $department]);
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // }
    
    // private function getDepartmentSalaries($department)
    // {
    //     $db = \App\Core\App::db();
        
    //     $stmt = $db->prepare("
    //         SELECT s.*, e.id as employee_id, u.name as employee_name
    //         FROM salaries s
    //         JOIN employees e ON s.employee_id = e.id
    //         JOIN users u ON e.user_id = u.id
    //         WHERE e.department = :department 
    //         AND s.deleted_at IS NULL
    //         ORDER BY s.created_at DESC
    //         LIMIT 10
    //     ");
    //     $stmt->execute(['department' => $department]);
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // }
    
    private function getDepartmentTasks($department)
    {
        $db = \App\Core\App::db();
        
        $stmt = $db->prepare("
            SELECT t.*, e.id as employee_id, u.name as employee_name,
                   creator.name as created_by_name
            FROM tasks t
            JOIN employees e ON t.assigned_to = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN users creator ON t.created_by = creator.id
            WHERE e.department = :department 
            ORDER BY t.due_date ASC, t.priority DESC
            LIMIT 10
        ");
        $stmt->execute(['department' => $department]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function employees()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        $employees = $this->getDepartmentEmployees($manager['department']);
        
        $this->render('manager/employees.php', [
            'manager' => $manager,
            'employees' => $employees
        ], 'manager/dashboard-layout.php');
    }
    
    public function leaves()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        $leaves = $this->getDepartmentLeaves($manager['department']);
        $stats = $this->getLeaveStatistics($manager['department']);
        
        $this->render('manager/leaves.php', [
            'manager' => $manager,
            'leaves' => $leaves,
            'leaveStats' => $stats
        ], 'manager/dashboard-layout.php');
    }
    
    public function salaries()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        $salaries = $this->getDepartmentSalaries($manager['department']);
        $stats = $this->getSalaryStatistics($manager['department']);
        
        $this->render('manager/salaries.php', [
            'manager' => $manager,
            'salaries' => $salaries,
            'salaryStats' => $stats
        ], 'manager/dashboard-layout.php');
    }
    
    public function tasks()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        $tasks = $this->getDepartmentTasks($manager['department']);
        $employees = $this->getDepartmentEmployees($manager['department']);
        
        $this->render('manager/tasks.php', [
            'manager' => $manager,
            'tasks' => $tasks,
            'employees' => $employees
        ], 'manager/dashboard-layout.php');
    }
    
    public function createTask()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $assigned_to = $_POST['assigned_to'] ?? '';
        $due_date = $_POST['due_date'] ?? '';
        $priority = $_POST['priority'] ?? 'medium';
        
        try {
            // التحقق من أن الموظف المعين ينتمي لقسم المدير
            $employee = Employee::find($assigned_to);
            if (!$employee || $employee['department'] !== $manager['department']) {
                throw new \Exception("لا يمكن تعيين المهمة لهذا الموظف");
            }
            
            $result = Task::create([
                'title' => $title,
                'description' => $description,
                'assigned_to' => $assigned_to,
                'due_date' => $due_date,
                'priority' => $priority,
                'created_by' => $_SESSION['user']['id']
            ]);
            
            if ($result) {
                $_SESSION['success'] = "تم إنشاء المهمة بنجاح";
            } else {
                $_SESSION['error'] = "فشل في إنشاء المهمة";
            }
            
        } catch (\Throwable $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: /employee-portal/public/manager/tasks");
        exit;
    }
    
    public function updateTaskStatus($taskId)
    {
        Auth::check();
        
        $status = $_POST['status'] ?? '';
        
        try {
            $manager = Employee::findByUserId($_SESSION['user']['id']);
            $task = Task::find($taskId);
            
            if (!$task) {
                throw new \Exception("المهمة غير موجودة");
            }
            
            // التحقق من أن المهمة تابعة لقسم المدير
            $employee = Employee::find($task['assigned_to']);
            if (!$employee || $employee['department'] !== $manager['department']) {
                throw new \Exception("لا يمكن تعديل هذه المهمة");
            }
            
            $result = Task::updateStatus($taskId, $status);
            
            if ($result) {
                $_SESSION['success'] = "تم تحديث حالة المهمة بنجاح";
            } else {
                $_SESSION['error'] = "فشل في تحديث حالة المهمة";
            }
            
        } catch (\Throwable $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: /employee-portal/public/manager/tasks");
        exit;
    }

    private function getSalaryStatistics($department)
    {
        $db = \App\Core\App::db();
        
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(amount + (amount * bonusPercentage / 100) - (amount * deductionPercentage / 100)) as total_amount
            FROM salaries s
            JOIN employees e ON s.employee_id = e.id
            WHERE e.department = :department 
            AND s.deleted_at IS NULL
        ");
        $stmt->execute(['department' => $department]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function getLeaveStatistics($department)
    {
        $db = \App\Core\App::db();
        
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(days_requested) as total_days
            FROM leaves l
            JOIN employees e ON l.employee_id = e.id
            WHERE e.department = :department 
            AND l.deleted_at IS NULL
        ");
        $stmt->execute(['department' => $department]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function evaluations()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        $evaluations = Evaluation::getDepartmentEvaluations($manager['department']);
        $employees = $this->getDepartmentEmployees($manager['department']);
        
        $this->render('manager/evaluations.php', [
            'manager' => $manager,
            'evaluations' => $evaluations,
            'employees' => $employees
        ], 'manager/dashboard-layout.php');
    }

    public function createEvaluation()
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        
        $employee_id = $_POST['employee_id'] ?? '';
        $performance_score = $_POST['performance_score'] ?? '';
        $quality_score = $_POST['quality_score'] ?? '';
        $punctuality_score = $_POST['punctuality_score'] ?? '';
        $teamwork_score = $_POST['teamwork_score'] ?? '';
        $comments = $_POST['comments'] ?? '';
        $evaluation_date = $_POST['evaluation_date'] ?? date('Y-m-d');
        $next_evaluation_date = $_POST['next_evaluation_date'] ?? '';
        
        try {
            // التحقق من أن الموظف ينتمي لقسم المدير
            $employee = Employee::find($employee_id);
            if (!$employee || $employee['department'] !== $manager['department']) {
                throw new \Exception("لا يمكن تقييم هذا الموظف");
            }
            
            // التحقق من عدم وجود تقييم حديث
            if (Evaluation::hasRecentEvaluation($employee_id, 3)) {
                throw new \Exception("هذا الموظف لديه تقييم حديث خلال آخر 3 أشهر");
            }
            
            $result = Evaluation::create([
                'employee_id' => $employee_id,
                'evaluator_id' => $_SESSION['user']['id'],
                'performance_score' => $performance_score,
                'quality_score' => $quality_score,
                'punctuality_score' => $punctuality_score,
                'teamwork_score' => $teamwork_score,
                'comments' => $comments,
                'evaluation_date' => $evaluation_date,
                'next_evaluation_date' => $next_evaluation_date
            ]);
            
            if ($result) {
                $_SESSION['success'] = "تم إضافة التقييم بنجاح";
            } else {
                $_SESSION['error'] = "فشل في إضافة التقييم";
            }
            
        } catch (\Throwable $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: /employee-portal/public/manager/evaluations");
        exit;
    }

    public function evaluationReport($employeeId)
    {
        Auth::check();
        
        $manager = Employee::findByUserId($_SESSION['user']['id']);
        $employee = Employee::find($employeeId);
        
        // التحقق من أن الموظف ينتمي لقسم المدير
        if (!$employee || $employee['department'] !== $manager['department']) {
            $_SESSION['error'] = "غير مسموح بالوصول لهذا التقرير";
            header("Location: /employee-portal/public/manager/evaluations");
            exit;
        }
        
        $evaluations = Evaluation::getEmployeeEvaluations($employeeId);
        $averageScores = Evaluation::getAverageScores($employeeId);
        
        $this->render('manager/evaluation-report.php', [
            'manager' => $manager,
            'employee' => $employee,
            'evaluations' => $evaluations,
            'averageScores' => $averageScores
        ], 'manager/dashboard-layout.php');
    }


    // في ManagerDashboardController
private function getDepartmentLeaves($department)
{
    $db = \App\Core\App::db();
    
    $stmt = $db->prepare("
        SELECT l.*, e.id as employee_id, u.name as employee_name,
               u2.name as approved_by_name
        FROM leaves l
        JOIN employees e ON l.employee_id = e.id
        JOIN users u ON e.user_id = u.id
        LEFT JOIN users u2 ON l.approved_by = u2.id
        WHERE e.department = :department 
        AND l.deleted_at IS NULL
        ORDER BY l.created_at DESC
        LIMIT 10
    ");
    $stmt->execute(['department' => $department]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

private function getDepartmentSalaries($department)
{
    $db = \App\Core\App::db();
    
    $stmt = $db->prepare("
        SELECT s.*, e.id as employee_id, u.name as employee_name,
               u2.name as approved_by_name
        FROM salaries s
        JOIN employees e ON s.employee_id = e.id
        JOIN users u ON e.user_id = u.id
        LEFT JOIN users u2 ON s.approved_by = u2.id
        WHERE e.department = :department 
        AND s.deleted_at IS NULL
        ORDER BY s.created_at DESC
        LIMIT 10
    ");
    $stmt->execute(['department' => $department]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
   public function leaveRequestForm()
{
    Auth::check();
    
    if ($_SESSION['user']['role'] !== 'manager') {
        header("Location: /employee-portal/public/".$_SESSION['user']['role']);
        exit;
    }
    
    $manager = Employee::findByUserId($_SESSION['user']['id']);
    
    if (!$manager) {
        $_SESSION['error'] = "لم يتم العثور على بيانات المدير";
        header("Location: /employee-portal/public/login");
        exit;
    }
    
    $this->render('manager/leave-request.php', [
        'manager' => $manager
    ], 'manager/dashboard-layout.php');
}

public function submitLeaveRequest()
{
    Auth::check();
    
    if ($_SESSION['user']['role'] !== 'manager') {
        header("Location: /employee-portal/public/".$_SESSION['user']['role']);
        exit;
    }
    
    $manager = Employee::findByUserId($_SESSION['user']['id']);
    
    if (!$manager) {
        $_SESSION['error'] = "لم يتم العثور على بيانات المدير";
        header("Location: /employee-portal/public/login");
        exit;
    }
    
    try {
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $type = $_POST['type'] ?? '';
        $reason = $_POST['reason'] ?? '';
        
        // التحقق من البيانات
        if (empty($start_date) || empty($end_date) || empty($type) || empty($reason)) {
            throw new \Exception("جميع الحقول مطلوبة");
        }
        
        // التحقق من صحة التواريخ
        if (strtotime($start_date) > strtotime($end_date)) {
            throw new \Exception("تاريخ البداية يجب أن يكون قبل تاريخ النهاية");
        }
        
        // حساب عدد الأيام
        $days_requested = $this->calculateLeaveDays($start_date, $end_date);
        
        // التحقق من رصيد الإجازات
        if ($manager['leaveBalance'] < $days_requested) {
            throw new \Exception("رصيد الإجازات غير كافي. الرصيد المتاح: {$manager['leaveBalance']} يوم");
        }
        
        // حفظ طلب الإجازة
        $result = Leave::create([
            'employee_id' => $manager['id'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'days_requested' => $days_requested,
            'type' => $type,
            'reason' => $reason,
            'status' => 'pending'
        ]);
        
        if ($result) {
            $_SESSION['success'] = "تم تقديم طلب الإجازة بنجاح. عدد الأيام: $days_requested";
            header("Location: /employee-portal/public/manager/leaves");
        } else {
            throw new \Exception("فشل في تقديم طلب الإجازة");
        }
        
    } catch (\Throwable $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: /employee-portal/public/manager/leave-request");
    }
    exit;
}

private function calculateLeaveDays($start_date, $end_date)
{
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('+1 day'); // لتضمين اليوم الأخير
    
    $interval = $start->diff($end);
    return $interval->days;
}


public function profile()
{
    Auth::check();
    
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    
    if (!$employee) {
        $_SESSION['error'] = "لم يتم العثور على بيانات الموظف";
        header("Location: /employee-portal/public/login");
        exit;
    }
    
    $employeeId = $employee['id'] ?? $employee['employee_id'];
    
    // جلب التقييمات
    $evaluations = Evaluation::getEmployeeEvaluations($employeeId);
    $averageScores = Evaluation::getAverageScores($employeeId);
    
    $this->render('manager/profile.php', [
        'employee' => $employee,
        'evaluations' => $evaluations,
        'averageScores' => $averageScores
    ], 'manager/dashboard-layout.php');
}

public function updateProfile()
{
    Auth::check();
    
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    $employeeId = $employee['id'] ?? $employee['employee_id'];
    
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    try {
        $result = Employee::updateProfile($employeeId, [
            'phone' => $phone,
            'address' => $address
        ]);
        
        if ($result) {
            $_SESSION['success'] = "تم تحديث الملف الشخصي بنجاح";
        } else {
            $_SESSION['error'] = "فشل في تحديث الملف الشخصي";
        }
        
    } catch (\Throwable $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    header("Location: /employee-portal/public/manager/profile");
    exit;
}


}