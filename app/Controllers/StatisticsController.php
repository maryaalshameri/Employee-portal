<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\User;
use App\Core\Auth;

class StatisticsController extends BaseController
{
    public function index()
    {
        Auth::check();
        
        // إحصائيات الموظفين
        $employeeStats = $this->getEmployeeStatistics();
        
        // إحصائيات الإجازات
        $leaveStats = $this->getLeaveStatistics();
        
        // إحصائيات الرواتب
        $salaryStats = $this->getSalaryStatistics();
        
        // إحصائيات المستخدمين
        $userStats = $this->getUserStatistics();
        
        // إحصائيات عامة
        $generalStats = $this->getGeneralStatistics();
        
        $this->render('admin/statistics/index.php', [
            'employeeStats' => $employeeStats,
            'leaveStats' => $leaveStats,
            'salaryStats' => $salaryStats,
            'userStats' => $userStats,
            'generalStats' => $generalStats
        ]);
    }
    
    private function getEmployeeStatistics()
    {
        $db = \App\Core\App::db();
        
        // عدد الموظفين الإجمالي
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM employees WHERE deleted_at IS NULL");
        $stmt->execute();
        $total = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // عدد الموظفين حسب القسم
        $stmt = $db->prepare("SELECT department, COUNT(*) as count FROM employees WHERE deleted_at IS NULL GROUP BY department");
        $stmt->execute();
        $byDepartment = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // عدد الموظفين حسب نوع العمل
        $stmt = $db->prepare("SELECT work_type, COUNT(*) as count FROM employees WHERE deleted_at IS NULL GROUP BY work_type");
        $stmt->execute();
        $byWorkType = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // أحدث الموظفين
        $stmt = $db->prepare("SELECT e.*, u.name FROM employees e JOIN users u ON e.user_id = u.id WHERE e.deleted_at IS NULL ORDER BY e.created_at DESC LIMIT 5");
        $stmt->execute();
        $recentEmployees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return [
            'total' => $total,
            'byDepartment' => $byDepartment,
            'byWorkType' => $byWorkType,
            'recentEmployees' => $recentEmployees
        ];
    }
    
    private function getLeaveStatistics()
    {
        $db = \App\Core\App::db();
        
        // إجمالي طلبات الإجازة
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM leaves WHERE deleted_at IS NULL");
        $stmt->execute();
        $total = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // طلبات الإجازة حسب الحالة
        $stmt = $db->prepare("SELECT status, COUNT(*) as count FROM leaves WHERE deleted_at IS NULL GROUP BY status");
        $stmt->execute();
        $byStatus = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // طلبات الإجازة حسب النوع
        $stmt = $db->prepare("SELECT type, COUNT(*) as count FROM leaves WHERE deleted_at IS NULL GROUP BY type");
        $stmt->execute();
        $byType = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // إجمالي أيام الإجازة
        $stmt = $db->prepare("SELECT SUM(days_requested) as total_days FROM leaves WHERE status = 'approved' AND deleted_at IS NULL");
        $stmt->execute();
        $totalDays = $stmt->fetch(\PDO::FETCH_ASSOC)['total_days'] ?? 0;
        
        // طلبات الإجازة الأخيرة
        $stmt = $db->prepare("
            SELECT l.*, u.name as employee_name 
            FROM leaves l 
            JOIN employees e ON l.employee_id = e.id 
            JOIN users u ON e.user_id = u.id 
            WHERE l.deleted_at IS NULL 
            ORDER BY l.created_at DESC 
            LIMIT 5
        ");
        $stmt->execute();
        $recentLeaves = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return [
            'total' => $total,
            'byStatus' => $byStatus,
            'byType' => $byType,
            'totalDays' => $totalDays,
            'recentLeaves' => $recentLeaves
        ];
    }
    
    private function getSalaryStatistics()
    {
        $db = \App\Core\App::db();
        
        // إجمالي الرواتب
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM salaries WHERE deleted_at IS NULL");
        $stmt->execute();
        $total = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // الرواتب حسب الحالة
        $stmt = $db->prepare("SELECT status, COUNT(*) as count FROM salaries WHERE deleted_at IS NULL GROUP BY status");
        $stmt->execute();
        $byStatus = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // إجمالي المبالغ
        $stmt = $db->prepare("
            SELECT 
                SUM(amount) as total_amount,
                SUM(amount * bonusPercentage / 100) as total_bonus,
                SUM(amount * deductionPercentage / 100) as total_deductions,
                SUM(amount + (amount * bonusPercentage / 100) - (amount * deductionPercentage / 100)) as net_total
            FROM salaries 
            WHERE status = 'approved' AND deleted_at IS NULL
        ");
        $stmt->execute();
        $amounts = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // متوسط الراتب
        $stmt = $db->prepare("SELECT AVG(amount) as avg_salary FROM salaries WHERE status = 'approved' AND deleted_at IS NULL");
        $stmt->execute();
        $avgSalary = $stmt->fetch(\PDO::FETCH_ASSOC)['avg_salary'] ?? 0;
        
        // أحدث الرواتب
        $stmt = $db->prepare("
            SELECT s.*, u.name as employee_name 
            FROM salaries s 
            JOIN employees e ON s.employee_id = e.id 
            JOIN users u ON e.user_id = u.id 
            WHERE s.deleted_at IS NULL 
            ORDER BY s.created_at DESC 
            LIMIT 5
        ");
        $stmt->execute();
        $recentSalaries = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return [
            'total' => $total,
            'byStatus' => $byStatus,
            'amounts' => $amounts,
            'avgSalary' => $avgSalary,
            'recentSalaries' => $recentSalaries
        ];
    }
    
    private function getUserStatistics()
    {
        $db = \App\Core\App::db();
        
        // إجمالي المستخدمين
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $total = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // المستخدمين حسب الدور
        $stmt = $db->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        $stmt->execute();
        $byRole = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // أحدث المستخدمين
        $stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
        $stmt->execute();
        $recentUsers = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return [
            'total' => $total,
            'byRole' => $byRole,
            'recentUsers' => $recentUsers
        ];
    }
    
    private function getGeneralStatistics()
    {
        $db = \App\Core\App::db();
        
        // عدد العناصر في سلة المحذوفات
        $stmt = $db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM employees WHERE deleted_at IS NOT NULL) as employees_trash,
                (SELECT COUNT(*) FROM leaves WHERE deleted_at IS NOT NULL) as leaves_trash,
                (SELECT COUNT(*) FROM salaries WHERE deleted_at IS NOT NULL) as salaries_trash
        ");
        $stmt->execute();
        $trashCounts = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // إحصائيات النظام
        $stmt = $db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM employees) as total_employees,
                (SELECT COUNT(*) FROM leaves) as total_leaves,
                (SELECT COUNT(*) FROM salaries) as total_salaries,
                (SELECT COUNT(*) FROM users) as total_users
        ");
        $stmt->execute();
        $systemStats = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return [
            'trashCounts' => $trashCounts,
            'systemStats' => $systemStats
        ];
    }
}