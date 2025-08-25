<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المهام الموكلة إلي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .priority-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .priority-high {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .priority-medium {
            background-color: #fef3c7;
            color: #d97706;
        }

        .priority-low {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
        }

        .status-todo {
            background-color: #e5e7eb;
            color: #374151;
        }

        .status-inprogress {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .status-done {
            background-color: #d1fae5;
            color: #059669;
        }

        .table-row {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .table-row:hover {
            background-color: #f8fafc;
            transform: translateX(-5px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(52, 152, 219, 0.4);
        }

        .empty-state {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
            border-radius: 12px;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #ef4444;
            border-radius: 12px;
        }

        .status-select {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .status-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .urgent-task {
            animation: pulse 2s infinite;
            border-left: 4px solid #dc2626;
        }

        @keyframes pulse {
            0% { background-color: #fff; }
            50% { background-color: #fef2f2; }
            100% { background-color: #fff; }
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            
            .responsive-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body class="py-8 px-4">
    <div class="container mx-auto max-w-6xl">
        <!-- رسائل التنبيه -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-success px-4 py-3 mb-6 flex items-center">
                <i class="fas fa-check-circle ml-2 text-green-600"></i>
                <span class="text-green-800"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error px-4 py-3 mb-6 flex items-center">
                <i class="fas fa-exclamation-circle ml-2 text-red-600"></i>
                <span class="text-red-800"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- العنوان الرئيسي -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks ml-2 text-blue-500"></i>
                    المهام الموكلة إلي
                </h1>
                <p class="text-gray-600 mt-2">إدارة ومتابعة المهام المسندة إليك</p>
            </div>
            <div class="flex items-center space-x-3 mt-4 md:mt-0">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    <?= isset($tasks) && is_array($tasks) ? count($tasks) : 0 ?> مهمة
                </span>
                <a href="/employee-portal/public/employee" class="btn-primary text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-home ml-2"></i>
                    الرئيسية
                </a>
            </div>
        </div>

        <!-- بطاقة قائمة المهام -->
        <div class="main-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list-check ml-2 text-blue-500"></i>
                    قائمة المهام
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">التصفية:</span>
                    <select class="status-select text-sm" onchange="filterTasks(this.value)">
                        <option value="all">جميع المهام</option>
                        <option value="todo">للعمل</option>
                        <option value="in_progress">قيد التنفيذ</option>
                        <option value="done">مكتملة</option>
                    </select>
                </div>
            </div>

            <?php if (isset($tasks) && is_array($tasks) && !empty($tasks)): ?>
            <div class="table-container">
                <table class="w-full responsive-table">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">عنوان المهمة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الوصف</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">تاريخ الاستحقاق</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الأولوية</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الحالة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($tasks as $task): ?>
                        <?php
                        $isUrgent = isset($task['due_date']) && 
                                   strtotime($task['due_date']) < strtotime('+3 days') && 
                                   $task['status'] !== 'done';
                        ?>
                        <tr class="table-row <?= $isUrgent ? 'urgent-task' : '' ?>" data-status="<?= $task['status'] ?>">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-task ml-2 text-blue-500"></i>
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($task['title']) ?></span>
                                    <?php if ($isUrgent): ?>
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs mr-2">عاجل!</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                <div class="truncate" title="<?= htmlspecialchars($task['description'] ?? 'لا يوجد وصف') ?>">
                                    <?= htmlspecialchars($task['description'] ?? 'لا يوجد وصف') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($task['due_date'])): ?>
                                    <span class="text-sm <?= $isUrgent ? 'text-red-600 font-bold' : 'text-gray-600' ?>">
                                        <i class="far fa-calendar-alt ml-1"></i>
                                        <?= $task['due_date'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">غير محدد</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $priorityClass = [
                                    'high' => 'priority-high',
                                    'medium' => 'priority-medium',
                                    'low' => 'priority-low'
                                ];
                                $priorityText = [
                                    'high' => 'عالي',
                                    'medium' => 'متوسط',
                                    'low' => 'منخفض'
                                ];
                                $priorityIcon = [
                                    'high' => 'fas fa-arrow-up',
                                    'medium' => 'fas fa-minus',
                                    'low' => 'fas fa-arrow-down'
                                ];
                                ?>
                                <span class="priority-badge <?= $priorityClass[$task['priority']] ?? 'priority-medium' ?> flex items-center">
                                    <i class="<?= $priorityIcon[$task['priority']] ?? 'fas fa-minus' ?> ml-1"></i>
                                    <?= $priorityText[$task['priority']] ?? 'متوسط' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusClass = [
                                    'todo' => 'status-todo',
                                    'in_progress' => 'status-inprogress',
                                    'done' => 'status-done'
                                ];
                                $statusText = [
                                    'todo' => 'للعمل',
                                    'in_progress' => 'قيد التنفيذ',
                                    'done' => 'مكتمل'
                                ];
                                $statusIcon = [
                                    'todo' => 'fas fa-circle',
                                    'in_progress' => 'fas fa-spinner',
                                    'done' => 'fas fa-check-circle'
                                ];
                                ?>
                                <span class="status-badge <?= $statusClass[$task['status']] ?>">
                                    <i class="<?= $statusIcon[$task['status']] ?> ml-1"></i>
                                    <?= $statusText[$task['status']] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="/employee-portal/public/employee/tasks/update-status/<?= $task['id'] ?>" method="POST">
                                    <select name="status" onchange="this.form.submit()" class="status-select text-sm">
                                        <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>للعمل</option>
                                        <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>قيد التنفيذ</option>
                                        <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>مكتمل</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- إحصائيات المهام -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <?php
                    $todoTasks = array_filter($tasks, fn($t) => $t['status'] === 'todo');
                    $inProgressTasks = array_filter($tasks, fn($t) => $t['status'] === 'in_progress');
                    $doneTasks = array_filter($tasks, fn($t) => $t['status'] === 'done');
                    $urgentTasks = array_filter($tasks, fn($t) => 
                        isset($t['due_date']) && 
                        strtotime($t['due_date']) < strtotime('+3 days') && 
                        $t['status'] !== 'done'
                    );
                    ?>
                    
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">
                            <?= count($todoTasks) ?>
                        </div>
                        <p class="text-sm text-blue-800">مهام للعمل</p>
                    </div>
                    
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">
                            <?= count($inProgressTasks) ?>
                        </div>
                        <p class="text-sm text-yellow-800">قيد التنفيذ</p>
                    </div>
                    
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            <?= count($doneTasks) ?>
                        </div>
                        <p class="text-sm text-green-800">مهام مكتملة</p>
                    </div>
                    
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">
                            <?= count($urgentTasks) ?>
                        </div>
                        <p class="text-sm text-red-800">مهام عاجلة</p>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="empty-state text-center py-12">
                <i class="fas fa-tasks text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">لا توجد مهام موكلة إليك حالياً</h3>
                <p class="text-gray-400 mb-4">سيتم عرض المهام هنا عندما يتم تكليفك بمهام جديدة</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // تصفية المهام حسب الحالة
        function filterTasks(status) {
            const rows = document.querySelectorAll('.table-row');
            
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    if (row.getAttribute('data-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // تأثيرات عند التحميل
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.table-row');
            
            tableRows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    row.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
</body>
</html>