<?php
use App\Core\Router;

use App\Controllers\EmployeeController;
use App\Controllers\EmployeeDashboardController;

$router = new Router();

// تسجيل الدخول والخروج أولًا
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@doLogin');
$router->get('logout', 'AuthController@logout');

$router->get('admin', 'HomeController@admin');
$router->get('manager', 'HomeController@manager');
//  $router->get('employee', 'HomeController@employee');


$router->get('', 'HomeController@index');

$router->get('admin/employees', 'EmployeeController@index');         // عرض الموظفين
$router->get('admin/employees/create', 'EmployeeController@createForm'); // إضافة موظف
$router->post('admin/employees/create', 'EmployeeController@store');     // حفظ موظف
$router->get('admin/employees/edit/{id}', 'EmployeeController@editForm'); // تعديل
$router->post('admin/employees/edit/{id}', 'EmployeeController@update'); // تحديث
$router->get('admin/employees/delete/{id}', 'EmployeeController@delete'); // حذف (Soft Delete)
$router->get('admin/employees/trash', 'EmployeeController@trash');       // سلة المهملات
$router->post('admin/employees/restore/{id}', 'EmployeeController@restore'); // استعادة
$router->post('admin/employees/delete-final/{id}', 'EmployeeController@deleteFinal');

$router->get('admin/leaves', 'AdminLeaveController@index');
$router->get('admin/leaves/pending', 'AdminLeaveController@pending');
$router->get('admin/leaves/show/{id}', 'AdminLeaveController@show');
$router->post('admin/leaves/approve/{id}', 'AdminLeaveController@approve');
$router->post('admin/leaves/reject/{id}', 'AdminLeaveController@reject');
$router->get('admin/leaves/delete/{id}', 'AdminLeaveController@delete');
$router->get('admin/leaves/trash', 'AdminLeaveController@trash');
$router->post('admin/leaves/restore/{id}', 'AdminLeaveController@restore');
$router->post('admin/leaves/delete-final/{id}', 'AdminLeaveController@deleteFinal');
$router->get('admin/statistics', 'StatisticsController@index');
$router->get('admin/leaves/my-approvals', 'AdminLeaveController@myApprovals');

$router->get('admin/salaries', 'AdminSalaryController@index');
$router->get('admin/salaries/pending', 'AdminSalaryController@pending');
$router->get('admin/salaries/create', 'AdminSalaryController@createForm');
$router->post('admin/salaries/create', 'AdminSalaryController@store');
$router->get('admin/salaries/show/{id}', 'AdminSalaryController@show');
$router->post('admin/salaries/approve/{id}', 'AdminSalaryController@approve');
$router->post('admin/salaries/reject/{id}', 'AdminSalaryController@reject');
$router->post('admin/salaries/delete/{id}', 'AdminSalaryController@delete');
$router->get('admin/salaries/trash', 'AdminSalaryController@trash');
$router->POST('admin/salaries/restore/{id}', 'AdminSalaryController@restore');
// إضافة هذا المسار في ملف web.php
$router->get('admin/employees/record/{id}', 'EmployeeController@showRecord');
$router->POST('admin/salaries/delete-final/{id}', 'AdminSalaryController@deleteFinal');
$router->get('admin/salaries/my-approvals', 'AdminSalaryController@myApprovals');
$router->post('admin/salaries/statistics', 'AdminSalaryController@statistics');

$router->get('admin/debug/evaluations', 'EmployeeController@checkEvaluations');
$router->get('admin/debug/form', 'EmployeeController@debugFormData');
// مسارات تقييم المدراء من قبل الأدمن
$router->get('admin/managers/evaluate/{id}', 'EmployeeController@evaluateManagerForm');
$router->post('admin/managers/evaluate/{id}', 'EmployeeController@evaluateManager');
$router->get('admin/managers/task/{id}', 'EmployeeController@addManagerTaskForm');
$router->post('admin/managers/task/{id}', 'EmployeeController@addManagerTask');
$router->post('admin/managers/update-task/{id}', 'EmployeeController@updateManagerTaskStatus');
$router->get('admin/managers', 'EmployeeController@managers');


$router->get('/employee', 'EmployeeDashboardController@dashboard');

$router->get('/employee/leaves', 'EmployeeDashboardController@leavesHistory');
$router->get('/employee/salaries', 'EmployeeDashboardController@salariesHistory');

$router->get('employee/tasks', 'EmployeeDashboardController@tasks');
$router->post('employee/tasks/update-status/{id}', 'EmployeeDashboardController@updateTaskStatus');
$router->get('employee/profile', 'EmployeeDashboardController@profile');
$router->post('employee/profile/update', 'EmployeeDashboardController@updateProfile');

$router->get('employee/leave-request', 'EmployeeDashboardController@leaveRequestForm');
$router->post('employee/leave-request', 'EmployeeDashboardController@submitLeaveRequest');

$router->get('manager/dashboard', 'ManagerDashboardController@dashboard');
$router->get('manager/employees', 'ManagerDashboardController@employees');
$router->get('manager/leaves', 'ManagerDashboardController@leaves');
$router->get('manager/salaries', 'ManagerDashboardController@salaries');
$router->get('manager/tasks', 'ManagerDashboardController@tasks');
$router->post('manager/tasks/create', 'ManagerDashboardController@createTask');
$router->post('manager/tasks/update-status/{id}', 'ManagerDashboardController@updateTaskStatus');
$router->get('manager/leave-request', 'ManagerDashboardController@leaveRequestForm');
$router->post('manager/leave-request', 'ManagerDashboardController@submitLeaveRequest');

$router->get('manager/evaluations', 'ManagerDashboardController@evaluations');
$router->post('manager/evaluations/create', 'ManagerDashboardController@createEvaluation');
$router->get('manager/evaluation-report/{id}', 'ManagerDashboardController@evaluationReport');
$router->get('manager/profile', 'ManagerDashboardController@profile');
$router->post('manager/profile/update', 'ManagerDashboardController@updateProfile');