<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل الموظف - <?= htmlspecialchars($employee['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
        }

        [data-theme="dark"] {
            --primary-color: #60a5fa;
            --secondary-color: #3b82f6;
            --text-color: #e5e7eb;
            --bg-color: #111827;
            --card-bg: #1f2937;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .employee-card {
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #ffedd5;
            color: #9a3412;
        }

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .employee-grid {
                grid-template-columns: 1fr;
            }
            
            .table-responsive table {
                min-width: 600px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">سجل الموظف: <?= htmlspecialchars($employee['name']) ?></h1>
                <p class="text-gray-600 mt-2">عرض المعلومات الشخصية وسجلات الإجازات والرواتب</p>
            </div>
            <a href="/employee-portal/public/admin/employees" class="mt-4 md:mt-0 flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                <span class="material-icons mr-1">arrow_back</span>
                العودة للقائمة
            </a>
        </div>

        <!-- معلومات الموظف الشخصية -->
        <div class="employee-card p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">المعلومات الشخصية</h2>
                <span class="material-icons text-blue-500 text-3xl">person</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">الاسم</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['name']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">البريد الإلكتروني</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['email']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">القسم</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['department']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">الوظيفة</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['position']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">تاريخ التعيين</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['hire_date']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">الراتب</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['salary']) ?> ر.س</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">الهاتف</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['phone'] ?? 'غير محدد') ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">العنوان</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['address'] ?? 'غير محدد') ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">نوع الدوام</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['work_type']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-gray-500 text-sm">رصيد الإجازات</div>
                    <div class="font-medium"><?= htmlspecialchars($employee['leaveBalance']) ?> يوم</div>
                </div>
            </div>
        </div>

        <!-- سجل الإجازات -->
        <div class="employee-card p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">سجل الإجازات</h2>
                <span class="material-icons text-blue-500 text-3xl">event</span>
            </div>

            <?php if (!empty($leaves)): ?>
            <div class="table-responsive">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-right font-bold text-gray-700 border">نوع الإجازة</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">تاريخ البداية</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">تاريخ النهاية</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">عدد الأيام</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">السبب</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">الحالة</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">تاريخ الطلب</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaves as $leave): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border"><?= htmlspecialchars($leave['type']) ?></td>
                            <td class="p-3 border"><?= htmlspecialchars($leave['start_date']) ?></td>
                            <td class="p-3 border"><?= htmlspecialchars($leave['end_date']) ?></td>
                            <td class="p-3 border"><?= htmlspecialchars($leave['days_requested']) ?></td>
                            <td class="p-3 border"><?= htmlspecialchars($leave['reason']) ?></td>
                            <td class="p-3 border">
                                <?php 
                                $statusClass = [
                                    'pending' => 'status-pending',
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected'
                                ][$leave['status']] ?? '';
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($leave['status']) ?>
                                </span>
                            </td>
                            <td class="p-3 border"><?= htmlspecialchars($leave['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <span class="material-icons text-gray-400 text-5xl mb-3">event_busy</span>
                <p class="text-gray-500">لا توجد إجازات مسجلة لهذا الموظف</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- سجل الرواتب -->
        <div class="employee-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">سجل الرواتب</h2>
                <span class="material-icons text-blue-500 text-3xl">payments</span>
            </div>

            <?php if (!empty($salaries)): ?>
            <div class="table-responsive">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-right font-bold text-gray-700 border">المبلغ</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">نسبة المكافأة</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">نسبة الخصم</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">صافي الراتب</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">تاريخ الدفع</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">الحالة</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">تاريخ الطلب</th>
                            <th class="p-3 text-right font-bold text-gray-700 border">تمت الموافقة بواسطة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salaries as $salary): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border"><?= htmlspecialchars($salary['amount']) ?> ر.س</td>
                            <td class="p-3 border"><?= htmlspecialchars($salary['bonusPercentage']) ?>%</td>
                            <td class="p-3 border"><?= htmlspecialchars($salary['deductionPercentage']) ?>%</td>
                            <td class="p-3 border font-medium">
                                <?php
                                $netSalary = $salary['amount'] + 
                                            ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                                            ($salary['amount'] * $salary['deductionPercentage'] / 100);
                                echo htmlspecialchars(number_format($netSalary, 2)) . ' ر.س';
                                ?>
                            </td>
                            <td class="p-3 border"><?= htmlspecialchars($salary['payment_date']) ?></td>
                            <td class="p-3 border">
                                <?php 
                                $statusClass = [
                                    'pending' => 'status-pending',
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected'
                                ][$salary['status']] ?? '';
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($salary['status']) ?>
                                </span>
                            </td>
                            <td class="p-3 border"><?= htmlspecialchars($salary['created_at']) ?></td>
                            <td class="p-3 border"><?= htmlspecialchars($salary['approved_by_name'] ?? 'لم تتم الموافقة بعد') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <span class="material-icons text-gray-400 text-5xl mb-3">account_balance_wallet</span>
                <p class="text-gray-500">لا توجد رواتب مسجلة لهذا الموظف</p>
            </div>
            <?php endif; ?>
           </div>
           <!-- التقييمات -->
           <div class="bg-white p-6 rounded-lg shadow my-6">
          <h2 class="text-xl font-bold mb-4">التقييمات</h2>
        
            <?php if (!empty($averageScores['total_evaluations'])): ?>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center p-4 bg-blue-50 rounded">
                <div class="text-2xl font-bold text-blue-600"><?= number_format($averageScores['avg_performance'], 1) ?></div>
                <div class="text-sm">أداء العمل</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded">
                <div class="text-2xl font-bold text-green-600"><?= number_format($averageScores['avg_quality'], 1) ?></div>
                <div class="text-sm">جودة العمل</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded">
                <div class="text-2xl font-bold text-yellow-600"><?= number_format($averageScores['avg_punctuality'], 1) ?></div>
                <div class="text-sm">الالتزام بالمواعيد</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded">
                <div class="text-2xl font-bold text-purple-600"><?= number_format($averageScores['avg_teamwork'], 1) ?></div>
                <div class="text-sm">العمل الجماعي</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التقييم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعدل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المقيّم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الملاحظات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($evaluations as $eval): ?>
                    <?php
                    $averageScore = ($eval['performance_score'] + $eval['quality_score'] + 
                                   $eval['punctuality_score'] + $eval['teamwork_score']) / 4;
                    $ratingColor = $averageScore >= 4 ? 'text-green-600' : 
                                  ($averageScore >= 3 ? 'text-yellow-600' : 'text-red-600');
                    ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <span class="font-medium">أداء:</span> <?= $eval['performance_score'] ?>/5<br>
                                <span class="font-medium">جودة:</span> <?= $eval['quality_score'] ?>/5<br>
                                <span class="font-medium">مواعيد:</span> <?= $eval['punctuality_score'] ?>/5<br>
                                <span class="font-medium">فريق:</span> <?= $eval['teamwork_score'] ?>/5
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xl font-bold <?= $ratingColor ?>">
                                <?= number_format($averageScore, 1) ?>/5
                            </span>
                        </td>
                        <td class="px-6 py-4"><?= $eval['evaluation_date'] ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($eval['evaluator_name']) ?></td>
                        <td class="px-6 py-4">
                            <?php if (!empty($eval['comments'])): ?>
                            <div class="text-sm text-gray-600 max-w-xs">
                                <?= nl2br(htmlspecialchars($eval['comments'])) ?>
                            </div>
                            <?php else: ?>
                            <span class="text-gray-400">لا توجد ملاحظات</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- المهام -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-bold mb-4">المهام</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوصف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الاستحقاق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                         <th>تم الإنشاء بواسطة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($task['title']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($task['description']) ?></td>
                        <td class="px-6 py-4"><?= $task['due_date'] ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs 
                                <?= $task['priority'] === 'high' ? 'bg-red-100 text-red-800' : '' ?>
                                <?= $task['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                <?= $task['priority'] === 'low' ? 'bg-green-100 text-green-800' : '' ?>">
                                <?= $task['priority'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs 
                                <?= $task['status'] === 'done' ? 'bg-green-100 text-green-800' : '' ?>
                                <?= $task['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' ?>
                                <?= $task['status'] === 'todo' ? 'bg-gray-100 text-gray-800' : '' ?>">
                                <?= $task['status'] ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($task['created_by_name'] ?? 'غير معروف') ?></td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </main>

    <script>
        // إمكانية التبديل بين الوضع النهاري والليلي
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const body = document.body;
            
            // التحقق من التفضيل المحفوظ
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.setAttribute('data-theme', 'dark');
                if (themeToggle) themeToggle.checked = true;
            }
            
            // تغيير الوضع عند النقر على المفتاح
            if (themeToggle) {
                themeToggle.addEventListener('change', function() {
                    if (this.checked) {
                        body.setAttribute('data-theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        body.removeAttribute('data-theme');
                        localStorage.setItem('theme', 'light');
                    }
                });
            }
        });
    </script>
</body>
</html>
