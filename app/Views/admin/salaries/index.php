<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">إدارة الرواتب</h2>

    <!-- رسائل النجاح/الخطأ -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- زر إضافة -->
    <div class="mb-4 flex justify-end">
        <a href="/employee-portal/public/admin/salaries/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-plus ml-2"></i> إضافة راتب جديد
        </a>
    </div>

    <!-- إحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">إجمالي الرواتب</p>
            <p class="text-2xl font-bold"><?= number_format($statistics['total_amount'] ?? 0, 2) ?> ﷼</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">قيد الانتظار</p>
            <p class="text-2xl font-bold"><?= $statistics['pending'] ?? 0 ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">معتمدة</p>
            <p class="text-2xl font-bold"><?= $statistics['approved'] ?? 0 ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">مرفوضة</p>
            <p class="text-2xl font-bold"><?= $statistics['rejected'] ?? 0 ?></p>
        </div>
    </div>

    <!-- تبويبات -->
    <div class="flex flex-col mb-4 space-x-4 gap-5 md:flex-row">
        <a href="/employee-portal/public/admin/salaries"
           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">جميع الرواتب</a>
        <a href="/employee-portal/public/admin/salaries/pending"
           class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">قيد الانتظار</a>
        <a href="/employee-portal/public/admin/salaries/my-approvals"
           class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">موافقتي</a>
        
        <a href="/employee-portal/public/admin/salaries/trash"
           class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">سلة المحذوفات</a>
    </div>

    <!-- جدول -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">الموظف</th>
                    <th class="px-4 py-2 border">المبلغ الأساسي</th>
                    <th class="px-4 py-2 border">المكافأة</th>
                    <th class="px-4 py-2 border">الخصم</th>
                    <th class="px-4 py-2 border">الصافي</th>
                    <th class="px-4 py-2 border">تاريخ الدفع</th>
                    <th class="px-4 py-2 border">الحالة</th>
                    <th class="px-4 py-2 border">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($salaries)): ?>
                    <?php foreach ($salaries as $index => $salary): ?>
                        <?php
                        $netSalary = $salary['amount']
                            + ($salary['amount'] * $salary['bonusPercentage'] / 100)
                            - ($salary['amount'] * $salary['deductionPercentage'] / 100);
                        ?>
                        <tr>
                            <td class="px-4 py-2 border"><?= $index + 1 ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($salary['employee_name']) ?></td>
                            <td class="px-4 py-2 border"><?= number_format($salary['amount'], 2) ?> ﷼</td>
                            <td class="px-4 py-2 border"><?= number_format($salary['bonusPercentage'], 2) ?>%</td>
                            <td class="px-4 py-2 border"><?= number_format($salary['deductionPercentage'], 2) ?>%</td>
                            <td class="px-4 py-2 border font-bold"><?= number_format($netSalary, 2) ?> ﷼</td>
                            <td class="px-4 py-2 border"><?= $salary['payment_date'] ?></td>
                            <td class="px-4 py-2 border">
                                <?php if ($salary['status'] == 'approved'): ?>
                                    <span class="text-green-600 font-semibold">معتمدة</span>
                                <?php elseif ($salary['status'] == 'pending'): ?>
                                    <span class="text-yellow-600 font-semibold">قيد الانتظار</span>
                                <?php else: ?>
                                    <span class="text-red-600 font-semibold">مرفوضة</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 border">
                                <a href="/employee-portal/public/admin/salaries/show/<?= $salary['id'] ?>"
                                   class="text-blue-500 hover:text-blue-700">عرض</a>

                                <?php if ($salary['status'] == 'pending'): ?>
                                    <form action="/employee-portal/public/admin/salaries/approve/<?= $salary['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-green-500 hover:text-green-700 ml-2">موافقة</button>
                                    </form>
                                    <form action="/employee-portal/public/admin/salaries/reject/<?= $salary['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">رفض</button>
                                    </form>
                                <?php endif; ?>

                                <form action="/employee-portal/public/admin/salaries/delete/<?= $salary['id'] ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="px-4 py-2 border text-center">لا توجد رواتب</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
