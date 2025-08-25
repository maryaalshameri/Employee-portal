<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">الرواتب قيد الانتظار</h2>

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

    <!-- تبويبات -->
    <div class="flex flex-col mb-4 space-x-4 gap-5 md:flex-row">
        <a href="/employee-portal/public/admin/salaries"
           class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">جميع الرواتب</a>
        <a href="/employee-portal/public/admin/salaries/pending"
           class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">قيد الانتظار</a>
        <a href="/employee-portal/public/admin/salaries/my-approvals"
           class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">موافقتي</a>
        
        <a href="/employee-portal/public/admin/salaries/trash"
           class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">سلة المحذوفات</a>
    </div>

    <!-- جدول الرواتب قيد الانتظار -->
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
                    <th class="px-4 py-2 border">تاريخ الإنشاء</th>
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
                            <td class="px-4 py-2 border"><?= $salary['created_at'] ?></td>
                            <td class="px-4 py-2 border">
                                <a href="/employee-portal/public/admin/salaries/show/<?= $salary['id'] ?>"
                                   class="text-blue-500 hover:text-blue-700">عرض</a>

                                <form action="/employee-portal/public/admin/salaries/approve/<?= $salary['id'] ?>" method="POST" class="inline">
                                    <button type="submit" class="text-green-500 hover:text-green-700 ml-2">موافقة</button>
                                </form>
                                <form action="/employee-portal/public/admin/salaries/reject/<?= $salary['id'] ?>" method="POST" class="inline">
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">رفض</button>
                                </form>

                                <form action="/employee-portal/public/admin/salaries/delete/<?= $salary['id'] ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="px-4 py-2 border text-center">لا توجد رواتب قيد الانتظار</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>