<?php
namespace App\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use App\Core\Auth;

class AdminSalaryController extends BaseController
{
    public function index()
    {
        Auth::check();
        $salaries = Salary::all();
        $hasPending = Salary::hasPendingSalaries();
        $statistics = Salary::getStatistics();
        
        $this->render('admin/salaries/index.php', [
            'salaries' => $salaries,
            'hasPending' => $hasPending,
            'statistics' => $statistics,
            'currentUserId' => $_SESSION['user']['id']
        ]);
    }
    
    public function pending()
    {
        Auth::check();
        $salaries = Salary::pending();
        
        $this->render('admin/salaries/pending.php', [
            'salaries' => $salaries,
            'hasPending' => true,
            'currentUserId' => $_SESSION['user']['id']
        ]);
    }
    
    public function createForm()
{
    Auth::check();
    $employees = Employee::all();
    
    // الحصول على رواتب جميع الموظفين
    $employeeSalaries = [];
    foreach ($employees as $employee) {
        $employeeSalaries[$employee['id']] = Employee::getBaseSalary($employee['id']);
    }
    
    $this->render('admin/salaries/create.php', [
        'employees' => $employees,
        'employeeSalaries' => $employeeSalaries
    ]);
}
    
   public function store()
{
    Auth::check();

    $employee_id = $_POST['employee_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $bonusPercentage = $_POST['bonusPercentage'] ?? 0;
    $deductionPercentage = $_POST['deductionPercentage'] ?? 0;
    $payment_date = $_POST['payment_date'] ?? '';
    $comments = $_POST['comments'] ?? '';
    
    // الحصول على الراتب الأساسي من جدول الموظفين للمقارنة
    $baseSalary = Employee::getBaseSalary($employee_id);
    
    try {
        // يمكنك إضافة تحقق هنا إذا أردت
        // مثلاً: إذا كان الراتب المدخل يختلف كثيراً عن الراتب الأساسي
        $salaryDifference = abs($amount - $baseSalary);
        
        $result = Salary::create([
            'employee_id' => $employee_id,
            'amount' => $amount,
            'bonusPercentage' => $bonusPercentage,
            'deductionPercentage' => $deductionPercentage,
            'payment_date' => $payment_date,
            'comments' => $comments
        ]);
        
        if ($result) {
            $_SESSION['success'] = "تم إضافة الراتب بنجاح وانتظار الموافقة";
            
            // إذا كان هناك فرق كبير، أضف تحذير
            if ($salaryDifference > ($baseSalary * 0.2)) { // فرق أكثر من 20%
                $_SESSION['warning'] = "ملاحظة: هناك فرق كبير بين الراتب المدخل والراتب الأساسي للموظف";
            }
            
        } else {
            $_SESSION['error'] = "فشل في إضافة الراتب";
        }
    } catch (\Throwable $e) {
        $_SESSION['error'] = "خطأ: " . $e->getMessage();
    }
    
    header("Location: /employee-portal/public/admin/salaries");
    exit;
}
    
    public function show($id)
    {
        Auth::check();
        $salary = Salary::find($id);
        
        
    if (!$salary) {
        $_SESSION['error'] = "الراتب غير موجود";
        header("Location: /employee-portal/public/admin/salaries");
        exit;
    }
    
    $this->render('admin/salaries/show.php', [
        'salary' => $salary,
        'currentUserId' => $_SESSION['user']['id']
    ]);
    }
    
    public function approve($id)
    {
        Auth::check();
        
        try {
            $currentUserId = $_SESSION['user']['id'];
            $comments = $_POST['comments'] ?? null;
            
            $result = Salary::approve($id, $currentUserId, $comments);
            
            if ($result) {
                $_SESSION['success'] = "تم الموافقة على الراتب بنجاح";
            } else {
                $_SESSION['error'] = "فشل في الموافقة على الراتب";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/salaries");
        exit;
    }
    
    public function reject($id)
    {
        Auth::check();
        
        try {
            $currentUserId = $_SESSION['user']['id'];
            $comments = $_POST['comments'] ?? null;
            
            $result = Salary::reject($id, $currentUserId, $comments);
            
            if ($result) {
                $_SESSION['success'] = "تم رفض الراتب بنجاح";
            } else {
                $_SESSION['error'] = "فشل في رفض الراتب";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/salaries");
        exit;
    }
    
    public function delete($id)
    {
        Auth::check();
        
        try {
            $result = Salary::softDelete($id);
            
            if ($result) {
                $_SESSION['success'] = "تم حذف الراتب بنجاح";
            } else {
                $_SESSION['error'] = "فشل في حذف الراتب";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/salaries");
        exit;
    }
    
    public function trash()
    {
        Auth::check();
        $salaries = Salary::allTrash();
        
        $this->render('admin/salaries/trash.php', [
            'salaries' => $salaries,
            'currentUserId' => $_SESSION['user']['id']
        ]);
    }
    
    public function restore($id)
    {
        Auth::check();
        
        try {
            $result = Salary::restore($id);
            
            if ($result) {
                $_SESSION['success'] = "تم استعادة الراتب بنجاح";
            } else {
                $_SESSION['error'] = "فشل في استعادة الراتب";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/salaries/trash");
        exit;
    }
    
   public function deleteFinal($id)
{
    Auth::check();

    try {
        $result = Salary::forceDelete($id);

        if ($result) {
            $_SESSION['success'] = "تم الحذف النهائي للراتب بنجاح";
        } else {
            $_SESSION['error'] = "فشل في الحذف النهائي";
        }
    } catch (\Throwable $e) {
        $_SESSION['error'] = "خطأ: " . $e->getMessage();
    }

    header("Location: /employee-portal/public/admin/salaries/trash");
    exit;
}

    
    public function myApprovals()
    {
        Auth::check();
        $currentUserId = $_SESSION['user']['id'];
        $salaries = Salary::getApprovedByUser($currentUserId);
        
        $this->render('admin/salaries/my-approvals.php', [
            'salaries' => $salaries,
            'currentUserId' => $currentUserId
        ]);
    }

    



    


    
    
    public function statistics()
    {
        Auth::check();
        $statistics = Salary::getStatistics();
        
        $this->render('admin/salaries/statistics.php', [
            'statistics' => $statistics
        ]);
    }

    
    
}