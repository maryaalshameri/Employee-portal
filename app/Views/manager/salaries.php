<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">رواتب قسم <?= htmlspecialchars($manager['department']) ?></h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نسبة المكافأة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نسبة الخصم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الصافي</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الدفع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الإنشاء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($salaries as $salary): ?>
                <?php
                $netSalary = $salary['amount'] + 
                           ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                           ($salary['amount'] * $salary['deductionPercentage'] / 100);
                ?>
                <tr>
                    <td class="px-6 py-4"><?= htmlspecialchars($salary['employee_name']) ?></td>
                    <td class="px-6 py-4"><?= number_format($salary['amount'], 2) ?> ريال</td>
                    <td class="px-6 py-4"><?= $salary['bonusPercentage'] ?>%</td>
                    <td class="px-6 py-4"><?= $salary['deductionPercentage'] ?>%</td>
                    <td class="px-6 py-4 font-bold"><?= number_format($netSalary, 2) ?> ريال</td>
                    <td class="px-6 py-4"><?= $salary['payment_date'] ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs 
                            <?= $salary['status'] === 'approved' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $salary['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                            <?= $salary['status'] === 'rejected' ? 'bg-red-100 text-red-800' : '' ?>">
                            <?= $salary['status'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4"><?= date('Y-m-d', strtotime($salary['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- إحصائيات الرواتب -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">إجمالي الرواتب</h3>
            <p class="text-2xl text-blue-600 font-bold">
                <?= number_format(array_sum(array_map(function($s) { 
                    return $s['amount'] + ($s['amount'] * $s['bonusPercentage'] / 100) - ($s['amount'] * $s['deductionPercentage'] / 100); 
                }, $salaries)), 2) ?> ريال
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">المعتمدة</h3>
            <p class="text-2xl text-green-600 font-bold">
                <?= count(array_filter($salaries, function($s) { return $s['status'] === 'approved'; })) ?>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">قيد المراجعة</h3>
            <p class="text-2xl text-yellow-600 font-bold">
                <?= count(array_filter($salaries, function($s) { return $s['status'] === 'pending'; })) ?>
            </p>
        </div>
    </div>
</div>