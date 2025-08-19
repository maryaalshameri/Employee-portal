<?php
namespace App\Controllers;

use App\Models\Employee;
use App\Core\Auth;
class EmployeeController {

    public function index() {
          Auth::check();
        $employees = Employee::all();
        require __DIR__ . '/../Views/employee/index.php';
    }

    public function createForm() {
        require __DIR__ . '/../Views/employee/create.php';
    }

    public function store() {
        Employee::create([
            'user_id' => $_POST['user_id'],
            'department' => $_POST['department'],
            'position' => $_POST['position'],
            'hire_date' => $_POST['hire_date'],
            'salary' => $_POST['salary'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
        ]);
        header("Location: /employee-portal/public/employee");
        exit;
    }

    public function editForm($id) {
        $employee = Employee::find($id);
        require __DIR__ . '/../Views/employee/edit.php';
    }

    public function update($id) {
        Employee::update($id, [
            'user_id' => $_POST['user_id'],
            'department' => $_POST['department'],
            'position' => $_POST['position'],
            'hire_date' => $_POST['hire_date'],
            'salary' => $_POST['salary'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
        ]);
        header("Location: /employee-portal/public/employee");
        exit;
    }

    public function delete($id) {
        Employee::delete($id);
        header("Location: /employee-portal/public/employee");
        exit;
    }
}
