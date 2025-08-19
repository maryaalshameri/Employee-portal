<?php
use App\Core\Router;
use App\Controllers\EmployeeController;

$router = new Router();

// تسجيل الدخول والخروج أولًا
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@doLogin');
$router->get('logout', 'AuthController@logout');

$router->get('admin', 'HomeController@admin');
$router->get('manager', 'HomeController@manager');
$router->get('employee', 'HomeController@employee');
$router->get('', 'HomeController@index');

$router->get('employee', 'EmployeeController@index');
$router->get('employee/create', 'EmployeeController@createForm');
$router->post('employee/create', 'EmployeeController@store');
$router->get('employee/edit', 'EmployeeController@editForm');
$router->post('employee/edit', 'EmployeeController@update');
$router->get('employee/delete', 'EmployeeController@delete');

$router->dispatch($_GET['url'] ?? '', $_SERVER['REQUEST_METHOD']);
