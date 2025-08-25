

    
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموظف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .action-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .notification-item {
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .notification-item:hover {
            transform: translateX(-5px);
        }

        .task-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
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
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-inprogress {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .status-done {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-todo {
            background-color: #e5e7eb;
            color: #374151;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="py-6 px-4">
    <div class="container mx-auto max-w-7xl">
        <!-- رسائل التنبيه -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- البطاقات الإحصائية -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- رصيد الإجازات -->
            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-600">رصيد الإجازات</h3>
                        <h2 class="text-3xl font-bold text-blue-600"><?= $stats['available_balance'] ?> يوم</h2>
                    </div>
                    <div class="stat-icon bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500">الإجازات المتاحة للاستخدام</p>
            </div>

            <!-- الإجازات المعتمدة -->
            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-600">الإجازات المعتمدة</h3>
                        <h2 class="text-3xl font-bold text-green-600"><?= $stats['approved_leaves'] ?></h2>
                    </div>
                    <div class="stat-icon bg-green-100 text-green-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500">طلبات الإجازة التي تمت الموافقة عليها</p>
            </div>

            <!-- طلبات قيد المراجعة -->
            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-600">طلبات قيد المراجعة</h3>
                        <h2 class="text-3xl font-bold text-yellow-600"><?= $stats['pending_leaves'] ?></h2>
                    </div>
                    <div class="stat-icon bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500">طلبات الإجازة قيد المراجعة</p>
            </div>

            <!-- إجمالي الإجازات -->
            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-600">إجمالي الإجازات</h3>
                        <h2 class="text-3xl font-bold text-purple-600"><?= $stats['total_leaves'] ?></h2>
                    </div>
                    <div class="stat-icon bg-purple-100 text-purple-600">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500">جميع طلبات الإجازة المقدمة</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- الإجراءات السريعة -->
            <div class="action-card p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-bolt ml-2 text-yellow-500"></i>
                    الإجراءات السريعة
                </h2>
                
                <div class="grid grid-cols-1 gap-4">
                    <a href="/employee-portal/public/employee/leave-request" class="flex items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                        <div class="bg-blue-100 p-3 rounded-lg ml-4">
                            <i class="fas fa-calendar-plus text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-blue-800">طلب إجازة جديدة</h3>
                            <p class="text-sm text-blue-600">تقديم طلب إجازة جديد</p>
                        </div>
                        <i class="fas fa-arrow-left text-blue-400 mr-auto"></i>
                    </a>
                    
                    <a href="/employee-portal/public/employee/tasks" class="flex items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                        <div class="bg-green-100 p-3 rounded-lg ml-4">
                            <i class="fas fa-tasks text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-800">عرض المهام</h3>
                            <p class="text-sm text-green-600">إدارة المهام الموكلة إليك</p>
                        </div>
                        <i class="fas fa-arrow-left text-green-400 mr-auto"></i>
                    </a>
                    
                    <a href="/employee-portal/public/employee/profile" class="flex items-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                        <div class="bg-purple-100 p-3 rounded-lg ml-4">
                            <i class="fas fa-user text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-purple-800">الملف الشخصي</h3>
                            <p class="text-sm text-purple-600">تعديل المعلومات الشخصية</p>
                        </div>
                        <i class="fas fa-arrow-left text-purple-400 mr-auto"></i>
                    </a>
                </div>
            </div>

            <!-- الإشعارات الحديثة -->
            <div class="action-card p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-bell ml-2 text-red-500"></i>
                    آخر الإشعارات
                </h2>
                
                <div class="scroll-container max-h-80 overflow-y-auto">
                    <?php 
                    $hasNotifications = false;
                    
                    // إشعارات الإجازات
                    if (!empty($pendingLeaves)): ?>
                        <?php foreach ($pendingLeaves as $leave): ?>
                            <div class="notification-item bg-yellow-50 border-yellow-400 mb-3">
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 p-2 rounded-full ml-3">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-yellow-800">طلب إجازة قيد المراجعة</h4>
                                        <p class="text-sm text-yellow-700"><?= $leave['days_requested'] ?> يوم (من <?= $leave['start_date'] ?> إلى <?= $leave['end_date'] ?>)</p>
                                        <p class="text-xs text-yellow-600 mt-1">بانتظار الموافقة من المدير</p>
                                    </div>
                                </div>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- إشعارات المهام -->
                    <?php if (!empty($recentTasks)): ?>
                        <?php foreach ($recentTasks as $task): ?>
                            <?php 
                            $statusClass = [
                                'todo' => 'status-todo',
                                'in_progress' => 'status-inprogress',
                                'done' => 'status-done',
                                'blocked' => 'status-rejected'
                            ];
                            $statusText = [
                                'todo' => 'جديدة',
                                'in_progress' => 'قيد التنفيذ',
                                'done' => 'مكتملة',
                                'blocked' => 'متوقفة'
                            ];
                            $iconClass = [
                                'todo' => 'fas fa-circle-plus text-gray-500',
                                'in_progress' => 'fas fa-spinner text-blue-500',
                                'done' => 'fas fa-check-circle text-green-500',
                                'blocked' => 'fas fa-ban text-red-500'
                            ];
                            ?>
                            <div class="notification-item bg-blue-50 border-blue-400 mb-3">
                                <div class="flex items-start">
                                    <div class="bg-blue-100 p-2 rounded-full ml-3">
                                        <i class="<?= $iconClass[$task['status']] ?>"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-semibold text-blue-800"><?= htmlspecialchars($task['title']) ?></h4>
                                            <span class="<?= $statusClass[$task['status']] ?> text-xs"><?= $statusText[$task['status']] ?></span>
                                        </div>
                                        <p class="text-sm text-blue-700"><?= htmlspecialchars(substr($task['description'], 0, 60)) ?>...</p>
                                        <?php if ($task['due_date']): ?>
                                            <p class="text-xs text-blue-600 mt-1">
                                                موعد التسليم: <?= $task['due_date'] ?>
                                                <?php if (strtotime($task['due_date']) < strtotime('+3 days')): ?>
                                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs mr-2">قريب!</span>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- إشعارات الرواتب -->
                    <?php if (!empty($salaryNotifications)): ?>
                        <?php foreach ($salaryNotifications as $salary): ?>
                            <div class="notification-item bg-green-50 border-green-400 mb-3">
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-2 rounded-full ml-3">
                                        <i class="fas fa-money-bill-wave text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-semibold text-green-800">
                                                <?php if ($salary['status'] === 'pending'): ?>
                                                    طلب راتب قيد المراجعة
                                                <?php elseif ($salary['status'] === 'approved'): ?>
                                                    تم اعتماد الراتب
                                                <?php else: ?>
                                                    تم رفض طلب الراتب
                                                <?php endif; ?>
                                            </h4>
                                            <span class="<?= $salary['status'] === 'approved' ? 'status-approved' : ($salary['status'] === 'rejected' ? 'status-rejected' : 'status-pending') ?> text-xs">
                                                <?= $salary['status'] ?>
                                            </span>
                                        </div>
                                        <?php if ($salary['status'] === 'approved'): ?>
                                            <p class="text-sm text-green-700">المبلغ: <?= number_format($salary['amount'], 2) ?> ر.ي</p>
                                            <?php if ($salary['payment_date']): ?>
                                                <p class="text-xs text-green-600 mt-1">تاريخ الصرف: <?= $salary['payment_date'] ?></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- إشعارات التقييمات -->
                    <?php if (!empty($evaluationNotifications)): ?>
                        <?php foreach ($evaluationNotifications as $evaluation): ?>
                            <div class="notification-item bg-purple-50 border-purple-400 mb-3">
                                <div class="flex items-start">
                                    <div class="bg-purple-100 p-2 rounded-full ml-3">
                                        <i class="fas fa-chart-line text-purple-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-purple-800">تقييم جديد</h4>
                                        <?php 
                                        $avgScore = ($evaluation['performance_score'] + $evaluation['quality_score'] + 
                                                   $evaluation['punctuality_score'] + $evaluation['teamwork_score']) / 4;
                                        ?>
                                        <div class="flex items-center mt-1">
                                            <span class="text-lg font-bold text-purple-700 mr-2"><?= number_format($avgScore, 1) ?>/5</span>
                                            <div class="text-yellow-400">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="<?= $i <= $avgScore ? 'fas fa-star' : 'far fa-star' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <p class="text-xs text-purple-600 mt-1">بتاريخ: <?= $evaluation['evaluation_date'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!$hasNotifications): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-bell-slash text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">لا توجد إشعارات حالياً</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- آخر المهام -->
            <div class="action-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-tasks ml-2 text-blue-500"></i>
                        آخر المهام
                    </h2>
                    <a href="/employee-portal/public/employee/tasks" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        عرض الكل
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
                
                <?php if (!empty($recentTasks)): ?>
                    <div class="space-y-4">
                        <?php foreach ($recentTasks as $task): ?>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($task['title']) ?></h3>
                                    <span class="<?= $task['status'] === 'done' ? 'status-done' : ($task['status'] === 'in_progress' ? 'status-inprogress' : 'status-todo') ?> text-xs">
                                        <?= $task['status'] === 'todo' ? 'جديدة' : ($task['status'] === 'in_progress' ? 'قيد التنفيذ' : 'مكتملة') ?>
                                    </span>
                                </div>
                                
                                <?php if ($task['description']): ?>
                                    <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars(substr($task['description'], 0, 80)) ?>...</p>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        <?php if ($task['due_date']): ?>
                                            <span class="text-xs text-gray-500">
                                                <i class="far fa-calendar-alt ml-1"></i>
                                                <?= $task['due_date'] ?>
                                                <?php if (strtotime($task['due_date']) < strtotime('+3 days')): ?>
                                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs mr-2">قريب!</span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <span class="text-xs <?= $task['priority'] === 'high' ? 'priority-high' : ($task['priority'] === 'medium' ? 'priority-medium' : 'priority-low') ?>">
                                        <?= $task['priority'] === 'high' ? 'عالي' : ($task['priority'] === 'medium' ? 'متوسط' : 'منخفض') ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-tasks text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">لا توجد مهام حالياً</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- آخر الرواتب -->
            <div class="action-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-file-invoice-dollar ml-2 text-green-500"></i>
                        آخر الرواتب
                    </h2>
                    <a href="/employee-portal/public/employee/salaries" class="text-green-600 hover:text-green-800 text-sm flex items-center">
                        عرض الكل
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
                
                <?php if (!empty($salaryNotifications)): ?>
                    <div class="space-y-4">
                        <?php foreach ($salaryNotifications as $salary): ?>
                            <?php
                            $netSalary = $salary['amount'] + 
                                       ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                                       ($salary['amount'] * $salary['deductionPercentage'] / 100);
                            ?>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="font-semibold text-gray-800"><?= number_format($netSalary, 2) ?> ر.ي</h3>
                                    <span class="<?= $salary['status'] === 'approved' ? 'status-approved' : ($salary['status'] === 'rejected' ? 'status-rejected' : 'status-pending') ?> text-xs">
                                        <?= $salary['status'] === 'approved' ? 'معتمد' : ($salary['status'] === 'rejected' ? 'مرفوض' : 'قيد المراجعة') ?>
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span>الأساسي:</span>
                                        <span class="font-medium"><?= number_format($salary['amount'], 2) ?> ر.ي</span>
                                    </div>
                                    <div>
                                        <span>المكافأة:</span>
                                        <span class="font-medium"><?= $salary['bonusPercentage'] ?>%</span>
                                    </div>
                                    <div>
                                        <span>الخصم:</span>
                                        <span class="font-medium"><?= $salary['deductionPercentage'] ?>%</span>
                                    </div>
                                    <div>
                                        <span>الصافي:</span>
                                        <span class="font-medium text-green-600"><?= number_format($netSalary, 2) ?> ر.ي</span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span><?= date('Y-m-d', strtotime($salary['created_at'])) ?></span>
                                    <?php if ($salary['payment_date']): ?>
                                        <span>دفع: <?= $salary['payment_date'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-file-invoice-dollar text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">لا توجد سجلات رواتب</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // تفعيل تأثيرات التحميل
        document.addEventListener('DOMContentLoaded', function() {
            // تأثير النبض للبطاقات الإحصائية
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate-pulse');
                    setTimeout(() => {
                        card.classList.remove('animate-pulse');
                    }, 2000);
                }, index * 300);
            });
        });
    </script>
</body>
</html>