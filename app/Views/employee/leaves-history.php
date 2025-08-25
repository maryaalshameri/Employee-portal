<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل الإجازات</title>
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

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .leave-type-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .type-annual {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .type-sick {
            background-color: #fce7f3;
            color: #be185d;
        }

        .type-emergency {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .type-other {
            background-color: #e5e7eb;
            color: #374151;
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
                    <i class="fas fa-history ml-2 text-blue-500"></i>
                    سجل الإجازات
                </h1>
                <p class="text-gray-600 mt-2">عرض جميع طلبات الإجازة السابقة</p>
            </div>
            <a href="/employee-portal/public/employee" class="btn-primary text-white px-6 py-2 rounded-lg mt-4 md:mt-0 flex items-center">
                <i class="fas fa-home ml-2"></i>
                رجوع إلى الرئيسية
            </a>
        </div>

        <!-- بطاقة سجل الإجازات -->
        <div class="main-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-alt ml-2 text-blue-500"></i>
                    جميع طلبات الإجازة
                </h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    <?= count($leaves) ?> طلب
                </span>
            </div>

            <?php if (!empty($leaves)): ?>
            <div class="table-container">
                <table class="w-full responsive-table">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">نوع الإجازة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">من تاريخ</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">إلى تاريخ</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">المدة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">السبب</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الحالة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">تاريخ الطلب</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($leaves as $leave): ?>
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <?php
                                $typeClass = [
                                    'annual' => 'type-annual',
                                    'sick' => 'type-sick', 
                                    'emergency' => 'type-emergency'
                                ];
                                $typeText = [
                                    'annual' => 'سنوية',
                                    'sick' => 'مرضية',
                                    'emergency' => 'طارئة'
                                ];
                                $typeIcon = [
                                    'annual' => 'fas fa-sun',
                                    'sick' => 'fas fa-heartbeat',
                                    'emergency' => 'fas fa-exclamation-triangle'
                                ];
                                $defaultClass = 'type-other';
                                $defaultText = 'أخرى';
                                $defaultIcon = 'fas fa-calendar';
                                ?>
                                <span class="leave-type-badge <?= $typeClass[$leave['type']] ?? $defaultClass ?> flex items-center">
                                    <i class="<?= $typeIcon[$leave['type']] ?? $defaultIcon ?> ml-1"></i>
                                    <?= $typeText[$leave['type']] ?? $defaultText ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $leave['start_date'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $leave['end_date'] ?></td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <?= $leave['days_requested'] ?> يوم
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="<?= htmlspecialchars($leave['reason']) ?>">
                                <?= htmlspecialchars($leave['reason']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusClass = [
                                    'approved' => 'status-approved',
                                    'pending' => 'status-pending',
                                    'rejected' => 'status-rejected'
                                ];
                                $statusIcon = [
                                    'approved' => 'fas fa-check-circle',
                                    'pending' => 'fas fa-clock',
                                    'rejected' => 'fas fa-times-circle'
                                ];
                                ?>
                                <span class="status-badge <?= $statusClass[$leave['status']] ?>">
                                    <i class="<?= $statusIcon[$leave['status']] ?> ml-1"></i>
                                    <?= $leave['status'] === 'approved' ? 'مقبولة' : 
                                       ($leave['status'] === 'pending' ? 'قيد المراجعة' : 'مرفوضة') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= date('Y-m-d', strtotime($leave['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state text-center py-12">
                <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">لا توجد طلبات إجازة سابقة</h3>
                <p class="text-gray-400 mb-4">لم تقم بتقديم أي طلبات إجازة حتى الآن</p>
                <a href="/employee-portal/public/employee/leave-request" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus-circle ml-1"></i>
                    تقديم طلب إجازة جديد
                </a>
            </div>
            <?php endif; ?>

            <?php if (!empty($leaves)): ?>
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            <?= count(array_filter($leaves, fn($l) => $l['status'] === 'approved')) ?>
                        </div>
                        <p class="text-sm text-green-800">طلبات مقبولة</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">
                            <?= count(array_filter($leaves, fn($l) => $l['status'] === 'pending')) ?>
                        </div>
                        <p class="text-sm text-yellow-800">طلبات قيد المراجعة</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">
                            <?= count(array_filter($leaves, fn($l) => $l['status'] === 'rejected')) ?>
                        </div>
                        <p class="text-sm text-red-800">طلبات مرفوضة</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
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