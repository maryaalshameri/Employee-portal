<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">موظفو قسم <?= htmlspecialchars($manager['department']) ?></h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوظيفة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع العمل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الراتب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رصيد الإجازات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td class="px-6 py-4"><?= htmlspecialchars($employee['name']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($employee['position']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($employee['work_type']) ?></td>
                    <td class="px-6 py-4"><?= number_format($employee['salary']) ?> ريال</td>
                    <td class="px-6 py-4"><?= $employee['leaveBalance'] ?> يوم</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>