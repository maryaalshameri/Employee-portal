<?php
namespace App\Controllers;


use App\Models\Employee;
use App\Core\Auth;
use App\Core\App;
use App\Models\Leave;      // إضافة هذا السطر
use App\Models\Salary;     // إضافة هذا السطر
use App\Models\Task; 
use App\Models\Evaluation; 

class EmployeeController extends BaseController
{
    public function index()
    {
        $employees = Employee::all();
        $this->render('admin/employees/index.php', [
            'employees' => $employees
        ]);
    }

    public function createForm()
    {
        $this->render('admin/employees/create.php');
    }

    public function store()
    {
        Auth::check();

        $name       = $_POST['name'] ?? '';
        $email      = $_POST['email'] ?? '';
        $password   = $_POST['password'] ?? '';
        $role       = $_POST['role'] ?? 'employee';
        $department = $_POST['department'] ?? '';
        $position   = $_POST['position'] ?? '';
        $hire_date  = $_POST['hire_date'] ?? '';
        $salary     = $_POST['salary'] ?? '';
        $phone      = $_POST['phone'] ?? null;
        $address    = $_POST['address'] ?? null;
        $work_type  = $_POST['work_type'] ?? 'full-time';
        $leaveBalance = (int)($_POST['leaveBalance'] ?? 0);

        $db = App::db();

        try {
            $db->beginTransaction();

            // التحقق من عدم تكرار الاسم
            if (Employee::isNameExists($name)) {
                throw new \Exception("هذا الاسم مستخدم بالفعل");
            }

            // التحقق من البريد الإلكتروني
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmtCheck->execute(['email' => $email]);
            if ($stmtCheck->fetch()) {
                throw new \Exception("هذا البريد الإلكتروني مستخدم بالفعل");
            }

            // التحقق من وجود مدير في القسم إذا كان الدور manager
            if ($role === 'manager' && Employee::hasManagerInDepartment($department)) {
                throw new \Exception("يوجد بالفعل مدير في قسم " . $department);
            }

            // إضافة المستخدم
            $stmtUser = $db->prepare("
                INSERT INTO users (name, email, password, role)
                VALUES (:name, :email, :password, :role)
            ");
            $stmtUser->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role'     => $role
            ]);
            $userId = $db->lastInsertId();

            // إضافة الموظف
            Employee::create([
                'user_id'      => $userId,
                'department'   => $department,
                'position'     => $position,
                'hire_date'    => $hire_date,
                'salary'       => $salary,
                'phone'        => $phone,
                'address'      => $address,
                'work_type'    => $work_type,
                'leaveBalance' => $leaveBalance,
            ]);

            $db->commit();
            $_SESSION['success_message'] = "تم إضافة الموظف بنجاح";
            header("Location:/employee-portal/public/admin/employees");
            exit;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            $_SESSION['error_message'] = "فشل الإضافة: " . $e->getMessage();
            header("Location:/employee-portal/public/admin/employees/create");
            exit;
        }
    }

    public function editForm($id)
    {
        $employee = Employee::find($id);
        $this->render('admin/employees/edit.php', [
            'employee' => $employee
        ]);
    }

    public function update($id)
    {
        Auth::check();

        $name        = $_POST['name'] ?? '';
        $email       = $_POST['email'] ?? '';
        $password    = $_POST['password'] ?? null;
        $role        = $_POST['role'] ?? 'employee';
        $department  = $_POST['department'] ?? '';
        $position    = $_POST['position'] ?? '';
        $hire_date   = $_POST['hire_date'] ?? '';
        $salary      = $_POST['salary'] ?? '';
        $phone       = $_POST['phone'] ?? null;
        $address     = $_POST['address'] ?? null;
        $work_type   = $_POST['work_type'] ?? 'full-time';
        $leaveBalance = (int)($_POST['leaveBalance'] ?? 0);

        $db = App::db();

        try {
            $db->beginTransaction();

            // الحصول على user_id للموظف
            $stmtEmp = $db->prepare("SELECT user_id FROM employees WHERE id = :id");
            $stmtEmp->execute(['id' => $id]);
            $userId = $stmtEmp->fetchColumn();

            if (!$userId) {
                throw new \Exception("الموظف غير موجود!");
            }

            // التحقق من عدم تكرار الاسم (استثناء المستخدم الحالي)
            if (Employee::isNameExists($name, $userId)) {
                throw new \Exception("هذا الاسم مستخدم بالفعل من قبل موظف آخر");
            }

            // التحقق من البريد الإلكتروني
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = :email AND id != :user_id");
            $stmtCheck->execute(['email' => $email, 'user_id' => $userId]);
            if ($stmtCheck->fetch()) {
                throw new \Exception("البريد الإلكتروني مستخدم من قبل موظف آخر!");
            }

            // التحقق من وجود مدير في القسم إذا كان الدور manager
            if ($role === 'manager') {
                $stmtCheckManager = $db->prepare("
                    SELECT COUNT(*) 
                    FROM employees e 
                    JOIN users u ON e.user_id = u.id 
                    WHERE e.department = :department 
                    AND u.role = 'manager' 
                    AND e.user_id != :user_id
                    AND e.deleted_at IS NULL
                ");
                $stmtCheckManager->execute([
                    'department' => $department,
                    'user_id' => $userId
                ]);
                
                if ($stmtCheckManager->fetchColumn() > 0) {
                    throw new \Exception("يوجد بالفعل مدير في قسم " . $department);
                }
            }

            // تحديث بيانات المستخدم
            if (!empty($password)) {
                $stmtUser = $db->prepare("
                    UPDATE users 
                    SET name = :name, email = :email, password = :password, role = :role
                    WHERE id = :id
                ");
                $stmtUser->execute([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'role'     => $role,
                    'id'       => $userId,
                ]);
            } else {
                $stmtUser = $db->prepare("
                    UPDATE users 
                    SET name = :name, email = :email, role = :role
                    WHERE id = :id
                ");
                $stmtUser->execute([
                    'name'  => $name,
                    'email' => $email,
                    'role'  => $role,
                    'id'    => $userId,
                ]);
            }

            // تحديث بيانات الموظف
            Employee::update($userId, [
                'department'   => $department,
                'position'     => $position,
                'hire_date'    => $hire_date,
                'salary'       => $salary,
                'phone'        => $phone,
                'address'      => $address,
                'work_type'    => $work_type,
                'leaveBalance' => $leaveBalance,
            ]);

            $db->commit();
            $_SESSION['success_message'] = "تم تحديث بيانات الموظف بنجاح";
            header("Location:/employee-portal/public/admin/employees");
            exit;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            $_SESSION['error_message'] = "فشل التعديل: " . $e->getMessage();
            header("Location:/employee-portal/public/admin/employees/edit/" . $id);
            exit;
        }
    }

    public function delete($id)
    {
        Auth::check();
        Employee::softDelete($id);
        $_SESSION['success_message'] = "تم نقل الموظف إلى سلة المهملات";
        header("Location: /employee-portal/public/admin/employees");
        exit;
    }

    public function trash()
    {
        $employees = Employee::allTrash();
        $this->render('admin/employees/trash.php', [
            'employees' => $employees
        ]);
    }

    public function restore($id)
    {
        Employee::employeeRestore($id);
        $_SESSION['success_message'] = "تم استعادة الموظف بنجاح";
        header("Location: /employee-portal/public/admin/employees/trash");
        exit;
    }

    public function deleteFinal($id)
    {
        $db = App::db();

        try {
            $db->beginTransaction();

            $stmtEmp = $db->prepare("SELECT user_id FROM employees WHERE id = :id");
            $stmtEmp->execute(['id' => $id]);
            $userId = $stmtEmp->fetchColumn();

            if ($userId) {
                $stmt1 = $db->prepare("DELETE FROM employees WHERE id = :id");
                $stmt1->execute(['id' => $id]);

                $stmt2 = $db->prepare("DELETE FROM users WHERE id = :user_id");
                $stmt2->execute(['user_id' => $userId]);
            }

            $db->commit();
            $_SESSION['success_message'] = "تم حذف الموظف نهائياً";
            header("Location: /employee-portal/public/admin/employees/trash");
            exit;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            $_SESSION['error_message'] = "فشل الحذف النهائي: " . $e->getMessage();
            header("Location: /employee-portal/public/admin/employees/trash");
            exit;
        }
    }
// إضافة هذه الدالة في نهاية EmployeeController class
public function showRecord($id)
{
    Auth::check();
    
    // الحصول على بيانات الموظف
    $employee = Employee::find($id);
    
    if (!$employee) {
        $_SESSION['error'] = "الموظف غير موجود";
        header("Location: /employee-portal/public/admin/employees");
        exit;
    }
    
    // الحصول على إجازات الموظف
    $leaves = Leave::getEmployeeLeaves($id);
    
    // الحصول على رواتب الموظف
    $salaries = Salary::getEmployeeSalaries($id);
    
    // الحصول على مهام الموظف
    $tasks = [];
    if (class_exists('App\Models\Task')) {
        $tasks = Task::getEmployeeTasks($id);
    }
    
    // الحصول على تقييمات الموظف
    $evaluations = [];
    $averageScores = [];
    if (class_exists('App\Models\Evaluation')) {
        $evaluations = Evaluation::getEmployeeEvaluations($id);
        $averageScores = Evaluation::getAverageScores($id);
    }
    
    $this->render('admin/employees/record.php', [
        'employee' => $employee,
        'leaves' => $leaves,
        'salaries' => $salaries,
        'tasks' => $tasks,
        'evaluations' => $evaluations,
        'averageScores' => $averageScores
    ]);
}

// في نهاية EmployeeController class
public function managers() {
    Auth::check();
    
    // جلب بيانات المدراء مع التقييمات والمهام
    $managers = Employee::getManagersWithEvaluationsAndTasks();
    
    $this->render('admin/managers/index.php', [
        'managers' => $managers
    ]);
}

// إضافة هذه الدوال في نهاية EmployeeController class

// public function evaluateManagerForm($managerId)
// {
//     Auth::check();
    
//     $manager = Employee::find($managerId);
    
//     if (!$manager || $manager['role'] !== 'manager') {
//         $_SESSION['error'] = "المدير غير موجود";
//         header("Location: /employee-portal/public/admin/managers");
//         exit;
//     }
    
//     $this->render('admin/managers/evaluate.php', [
//         'manager' => $manager
//     ]);
// }

// public function evaluateManager($managerId)
// {
//     Auth::check();
    
//     try {
//         $manager = Employee::find($managerId);
        
//         if (!$manager || $manager['role'] !== 'manager') {
//             throw new \Exception("المدير غير موجود");
//         }
        
//         $performance_score = $_POST['performance_score'] ?? 0;
//         $quality_score = $_POST['quality_score'] ?? 0;
//         $punctuality_score = $_POST['punctuality_score'] ?? 0;
//         $teamwork_score = $_POST['teamwork_score'] ?? 0;
//         $comments = $_POST['comments'] ?? '';
//         $evaluation_date = $_POST['evaluation_date'] ?? date('Y-m-d');
        
//         $result = Evaluation::create([
//             'employee_id' => $managerId,
//             'evaluator_id' => $_SESSION['user']['id'],
//             'performance_score' => $performance_score,
//             'quality_score' => $quality_score,
//             'punctuality_score' => $punctuality_score,
//             'teamwork_score' => $teamwork_score,
//             'comments' => $comments,
//             'evaluation_date' => $evaluation_date,
//             'next_evaluation_date' => date('Y-m-d', strtotime('+6 months'))
//         ]);
        
//         if ($result) {
//             $_SESSION['success'] = "تم تقييم المدير بنجاح";
//         } else {
//             $_SESSION['error'] = "فشل في تقييم المدير";
//         }
        
//     } catch (\Throwable $e) {
//         $_SESSION['error'] = $e->getMessage();
//     }
    
//     header("Location: /employee-portal/public/admin/managers");
//     exit;
// }

// public function addManagerTaskForm($managerId)
// {
//     Auth::check();
    
//     $manager = Employee::find($managerId);
    
//     if (!$manager || $manager['role'] !== 'manager') {
//         $_SESSION['error'] = "المدير غير موجود";
//         header("Location: /employee-portal/public/admin/managers");
//         exit;
//     }
    
//     $this->render('admin/managers/add-task.php', [
//         'manager' => $manager
//     ]);
// }

// public function addManagerTask($managerId)
// {
//     Auth::check();
    
//     try {
//         $manager = Employee::find($managerId);
        
//         if (!$manager || $manager['role'] !== 'manager') {
//             throw new \Exception("المدير غير موجود");
//         }
        
//         $title = $_POST['title'] ?? '';
//         $description = $_POST['description'] ?? '';
//         $due_date = $_POST['due_date'] ?? '';
//         $priority = $_POST['priority'] ?? 'medium';
        
//         if (empty($title) || empty($due_date)) {
//             throw new \Exception("جميع الحقول المطلوبة يجب ملؤها");
//         }
        
//         $result = Task::create([
//             'title' => $title,
//             'description' => $description,
//             'assigned_to' => $managerId,
//             'due_date' => $due_date,
//             'priority' => $priority,
//             'created_by' => $_SESSION['user']['id']
//         ]);
        
//         if ($result) {
//             $_SESSION['success'] = "تم إضافة المهمة للمدير بنجاح";
//         } else {
//             $_SESSION['error'] = "فشل في إضافة المهمة";
//         }
        
//     } catch (\Throwable $e) {
//         $_SESSION['error'] = $e->getMessage();
//     }
    
//     header("Location: /employee-portal/public/admin/managers");
//     exit;
// }
public function checkEvaluations()
{
    Auth::check();
    
    $db = \App\Core\App::db();
    
    // التحقق من وجود تقييمات في قاعدة البيانات
    $stmt = $db->prepare("
        SELECT e.*, u.name as employee_name, eval.name as evaluator_name
        FROM evaluations e
        JOIN employees emp ON e.employee_id = emp.id
        JOIN users u ON emp.user_id = u.id
        JOIN users eval ON e.evaluator_id = eval.id
        WHERE u.role = 'manager'
        ORDER BY e.evaluation_date DESC
    ");
    
    $stmt->execute();
    $evaluations = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    echo "<h2>التقييمات الموجودة في قاعدة البيانات:</h2>";
    echo "<pre>";
    print_r($evaluations);
    echo "</pre>";
    
    // التحقق من بيانات المدراء
    $stmt2 = $db->prepare("
        SELECT e.id, u.name, u.role, e.department
        FROM employees e
        JOIN users u ON e.user_id = u.id
        WHERE u.role = 'manager'
    ");
    
    $stmt2->execute();
    $managers = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
    
    echo "<h2>المدراء في النظام:</h2>";
    echo "<pre>";
    print_r($managers);
    echo "</pre>";
}

public function evaluateManager($managerId)
{
    Auth::check();
    
    // تأكد من أن المستخدم أدمن
    if ($_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "غير مصرح بهذا الإجراء";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    $employee_id = $_POST['employee_id'] ?? $managerId;
    $evaluator_id = $_SESSION['user']['id'];
    
    // التحقق من وجود تقييم خلال آخر 3 أشهر
    if (Evaluation::hasRecentEvaluation($employee_id, 3)) {
        $_SESSION['error'] = "هذا المدير لديه تقييم حديث خلال آخر 3 أشهر. لا يمكن إضافة تقييم جديد الآن.";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    $data = [
        'employee_id' => $employee_id,
        'evaluator_id' => $evaluator_id,
        'performance_score' => $_POST['performance_score'],
        'quality_score' => $_POST['quality_score'],
        'punctuality_score' => $_POST['punctuality_score'],
        'teamwork_score' => $_POST['teamwork_score'],
        'comments' => $_POST['comments'] ?? '',
        'evaluation_date' => $_POST['evaluation_date'] ?? date('Y-m-d')
    ];
    
    if (!empty($_POST['next_evaluation_date'])) {
        $data['next_evaluation_date'] = $_POST['next_evaluation_date'];
    }
    
    $result = Evaluation::create($data);
    
    if ($result) {
        $_SESSION['success'] = "تم إضافة التقييم بنجاح";
    } else {
        $_SESSION['error'] = "فشل في إضافة التقييم";
    }
    
    header("Location: /employee-portal/public/admin/managers");
    exit;
}
public function testManagerEvaluation()
{
    try {
        error_log("=== TESTING MANAGER EVALUATION ===");
        
        // بيانات اختبارية
        $testData = [
            'employee_id' => 3, // ID مدير موجود
            'evaluator_id' => 1, // ID أدمن
            'performance_score' => 5,
            'quality_score' => 4,
            'punctuality_score' => 5,
            'teamwork_score' => 4,
            'comments' => 'تقييم اختباري من الأدمن',
            'evaluation_date' => date('Y-m-d'),
            'next_evaluation_date' => date('Y-m-d', strtotime('+6 months'))
        ];
        
        error_log("Test Data: " . print_r($testData, true));
        
        // استخدام دالة التقييم
        $result = Evaluation::create($testData);
        
        if ($result) {
            error_log("Test evaluation SUCCESS!");
            echo "تم إضافة التقييم الاختباري بنجاح!";
            
            // التحقق من وجود التقييم في قاعدة البيانات
            $db = \App\Core\App::db();
            $stmt = $db->prepare("SELECT * FROM evaluations WHERE employee_id = ? ORDER BY id DESC LIMIT 1");
            $stmt->execute([$testData['employee_id']]);
            $lastEvaluation = $stmt->fetch();
            
            error_log("Last evaluation in DB: " . print_r($lastEvaluation, true));
            echo "<pre>آخر تقييم في DB: " . print_r($lastEvaluation, true) . "</pre>";
            
        } else {
            error_log("Test evaluation FAILED!");
            echo "فشل في إضافة التقييم الاختباري!";
        }
        
    } catch (\Throwable $e) {
        error_log("Test error: " . $e->getMessage());
        echo "خطأ: " . $e->getMessage();
    }
}

public function evaluateManagerForm($managerId)
{
    Auth::check();
    
    if ($_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "غير مصرح بهذا الإجراء";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    $manager = Employee::find($managerId);
    if (!$manager) {
        $_SESSION['error'] = "المدير غير موجود";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    // التحقق من آخر تقييم هنا في الكونترولر
    $hasRecentEvaluation = \App\Models\Evaluation::hasRecentEvaluation($managerId, 3);
    
    $this->render('admin/managers/evaluate.php', [
        'manager' => $manager,
        'hasRecentEvaluation' => $hasRecentEvaluation // تمرير النتيجة إلى العرض
    ]);
}
public function addManagerTaskForm($managerId)
{
    Auth::check();
    
    if ($_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "غير مصرح بهذا الإجراء";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    $manager = Employee::find($managerId);
    if (!$manager) {
        $_SESSION['error'] = "المدير غير موجود";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    // جلب مهام المدير
    $tasks = $this->getManagerTasks($managerId);
    
    $this->render('admin/managers/tasks.php', [
        'manager' => $manager,
        'tasks' => $tasks
    ]);
}

private function getManagerTasks($managerId)
{
    $db = \App\Core\App::db();
    
    $stmt = $db->prepare("
        SELECT t.*, creator.name as created_by_name
        FROM tasks t
        LEFT JOIN users creator ON t.created_by = creator.id
        WHERE t.assigned_to = :manager_id 
        ORDER BY t.due_date ASC, t.priority DESC
    ");
    $stmt->execute(['manager_id' => $managerId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public function addManagerTask($managerId)
{
    Auth::check();
    
    if ($_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "غير مصرح بهذا الإجراء";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $due_date = $_POST['due_date'] ?? '';
    $priority = $_POST['priority'] ?? 'medium';
    
    try {
        $result = Task::create([
            'title' => $title,
            'description' => $description,
            'assigned_to' => $managerId,
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
    
    header("Location: /employee-portal/public/admin/managers/task/" . $managerId);
    exit;
}

public function updateManagerTaskStatus($taskId)
{
    Auth::check();
    
    if ($_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "غير مصرح بهذا الإجراء";
        header("Location: /employee-portal/public/admin/managers");
        exit;
    }
    
    $status = $_POST['status'] ?? '';
    
    try {
        $result = Task::updateStatus($taskId, $status);
        
        if ($result) {
            $_SESSION['success'] = "تم تحديث حالة المهمة بنجاح";
        } else {
            $_SESSION['error'] = "فشل في تحديث حالة المهمة";
        }
        
    } catch (\Throwable $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    // الرجوع إلى صفحة المهام
    $task = Task::find($taskId);
    header("Location: /employee-portal/public/admin/managers/task/" . $task['assigned_to']);
    exit;
}

}
