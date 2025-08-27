<?php
namespace App\Controllers;

use App\Models\Leave;
use App\Models\Salary;
use App\Models\Employee;
use App\Core\Auth;
use App\Models\Task;
use App\Models\Evaluation;
class EmployeeDashboardController extends BaseController
{
// في دالة dashboard
public function dashboard()
{
    Auth::check();
    
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    
    if (!$employee) {
        $_SESSION['error'] = "لم يتم العثور على بيانات الموظف";
        header("Location: /employee-portal/public/login");
        exit;
    }
    
    $employeeId = $employee['id'] ?? $employee['employee_id'];
    
    // الإجازات
    $leaves = Leave::getEmployeeLeaves($employeeId);
    $pendingLeaves = Leave::getPendingEmployeeLeaves($employeeId);
    
    // المهام
    $recentTasks = Task::getRecentEmployeeTasks($employeeId, 3);
    
    // الرواتب
    $salaryNotifications = Salary::getRecentEmployeeSalaries($employeeId, 3);
    
    // التقييمات (للموظفين فقط)
    $evaluationNotifications = [];
    if ($_SESSION['user']['role'] === 'employee') {
        $evaluationNotifications = Evaluation::getRecentEmployeeEvaluations($employeeId, 2);
    }
    
    // الإحصائيات
    $stats = [
        'total_leaves' => count($leaves),
        'pending_leaves' => count($pendingLeaves),
        'approved_leaves' => count(array_filter($leaves, function($l) { 
            return $l['status'] === 'approved'; 
        })),
        'available_balance' => $employee['leaveBalance'] ?? 0
    ];

    $this->render('employee/dashboard.php', [
        'employee' => $employee,
        'leaves' => $leaves,
        'pendingLeaves' => $pendingLeaves,
        'recentTasks' => $recentTasks,
        'salaryNotifications' => $salaryNotifications,
        'evaluationNotifications' => $evaluationNotifications,
        'stats' => $stats
    ], 'employee/dashboard-layout.php');
}



    public function submitSalaryRequest()
    {
        Auth::check();
        
        $employeeId = $_SESSION['user']['employee_id'];
        
        try {
            // التحقق من آخر طلب راتب
            $lastRequest = Salary::getLastEmployeeSalaryRequest($employeeId);
            
            if ($lastRequest && $lastRequest['status'] === 'pending') {
                throw new \Exception("لديك طلب راتب قيد المراجعة بالفعل");
            }
            
            // التحقق من المدة بين الطلبات (مرة واحدة شهرياً)
            if ($lastRequest) {
                $lastDate = new \DateTime($lastRequest['created_at']);
                $currentDate = new \DateTime();
                $interval = $lastDate->diff($currentDate);
                
                if ($interval->days < 30) {
                    throw new \Exception("يمكنك طلب الراتب مرة واحدة كل شهر");
                }
            }
            
            // إنشاء طلب الراتب
            $result = Salary::createEmployeeRequest($employeeId);
            
            if ($result) {
                $_SESSION['success'] = "تم تقديم طلب الراتب بنجاح وانتظار الموافقة";
            } else {
                $_SESSION['error'] = "فشل في تقديم طلب الراتب";
            }
            
        } catch (\Throwable $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: /employee-portal/public/employee/salaries");
        exit;
    }

    public function leavesHistory()
    {
        Auth::check();
         $employeeId = $this->getEmployeeId();
        $employeeId = $_SESSION['user']['employee_id'];
       
        $leaves = Leave::getEmployeeLeaves($employeeId);
        
        $this->render('employee/leaves-history.php', [
            'leaves' => $leaves
        ], 'employee/dashboard-layout.php');
    }

    public function salariesHistory()
    {
        Auth::check();
       $employeeId = $this->getEmployeeId();
        $employeeId = $_SESSION['user']['employee_id'];
        $salaries = Salary::getEmployeeSalaries($employeeId);
        
        $this->render('employee/salaries-history.php', [
            'salaries' => $salaries
        ], 'employee/dashboard-layout.php');
    }

    private function getEmployeeId() {
    if (isset($_SESSION['user']['employee_id'])) {
        return $_SESSION['user']['employee_id'];
    }
    
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    if ($employee) {
        $employeeId = $employee['id'] ?? null;
        $_SESSION['user']['employee_id'] = $employeeId;
        return $employeeId;
    }
    
    return null;
}

// في EmployeeDashboardController
public function leaveRequestForm()
{
    Auth::check();
    
    // المدير يمكنه تقديم طلبات إجازة أيضاً
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    
    $this->render('employee/leave-request.php', [
        'employee' => $employee
    ], 'employee/dashboard-layout.php'); // استخدام layout المدير
}

public function salaryRequestForm()
{
    Auth::check();
    
    // المدير يمكنه تقديم طلبات راتب أيضاً
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    $lastSalaryRequest = Salary::getLastEmployeeSalaryRequest($_SESSION['user']['employee_id']);
    
    $this->render('employee/salary-request.php', [
        'employee' => $employee,
        'lastSalaryRequest' => $lastSalaryRequest
    ], 'employee/dashboard-layout.php'); // استخدام layout المدير
}

// في EmployeeDashboardController.php
public function tasks()
{
    Auth::check();
    
    $employeeId = $this->getEmployeeId();
    $tasks = Task::getEmployeeTasks($employeeId);
    
    $this->render('employee/tasks.php', [
        'tasks' => $tasks,
        'employee' => Employee::findByUserId($_SESSION['user']['id'])
    ], 'employee/dashboard-layout.php');
}

public function updateTaskStatus($taskId)
{
    Auth::check();
    
    $status = $_POST['status'] ?? '';
    $employeeId = $this->getEmployeeId();
    
    try {
        $task = Task::find($taskId);
        
        if (!$task || $task['assigned_to'] != $employeeId) {
            throw new \Exception("غير مصرح بتحديث هذه المهمة");
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
    
    header("Location: /employee-portal/public/employee/tasks");
    exit;
}



public function updateProfile()
{
    Auth::check();
    
    $employeeId = $this->getEmployeeId();
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
    
    header("Location: /employee-portal/public/employee/profile");
    exit;
}


public function submitLeaveRequest()
{
    Auth::check();
    
    $employeeId = $this->getEmployeeId();
    $employee = Employee::findByUserId($_SESSION['user']['id']);
    
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    $type = $_POST['type'] ?? '';
    $reason = $_POST['reason'] ?? '';
    
    try {
        // التحقق من صحة التواريخ
        if (empty($startDate) || empty($endDate)) {
            throw new \Exception("يجب تحديد تاريخ البداية والنهاية");
        }
        
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        
        if ($end <= $start) {
            throw new \Exception("تاريخ النهاية يجب أن يكون بعد تاريخ البداية");
        }
        
        // حساب عدد الأيام
        $interval = $start->diff($end);
        $daysRequested = $interval->days + 1;
        
        // التحقق من رصيد الإجازات
        if ($employee['leaveBalance'] < $daysRequested) {
            throw new \Exception("رصيد الإجازات غير كافي. الرصيد المتاح: {$employee['leaveBalance']} يوم ");
        }
        
        // إنشاء طلب الإجازة
        $result = Leave::create([
            'employee_id' => $employeeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_requested' => $daysRequested,
            'type' => $type,
            'reason' => $reason,
            'status' => 'pending'
        ]);
        
        if ($result) {
            $_SESSION['success'] = "تم تقديم طلب الإجازة بنجاح وانتظار الموافقة";
        } else {
            $_SESSION['error'] = "فشل في تقديم طلب الإجازة";
        }
        
    } catch (\Throwable $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    header("Location: /employee-portal/public/employee/leaves");
    exit;
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
    
    $this->render('employee/profile.php', [
        'employee' => $employee,
        'evaluations' => $evaluations,
        'averageScores' => $averageScores
    ], 'employee/dashboard-layout.php');
}
}