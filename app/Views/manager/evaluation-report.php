<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">تقرير تقييم الموظف: <?= htmlspecialchars($employee['name']) ?></h1>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-bold mb-4">معلومات الموظف</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <strong>القسم:</strong> <?= htmlspecialchars($employee['department']) ?>
            </div>
            <div>
                <strong>الوظيفة:</strong> <?= htmlspecialchars($employee['position']) ?>
            </div>
            <div>
                <strong>نوع العمل:</strong> <?= htmlspecialchars($employee['work_type']) ?>
            </div>
        </div>
    </div>

    <!-- المعدلات العامة -->
    <?php if ($averageScores['total_evaluations'] > 0): ?>
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-bold mb-4">المعدلات العامة</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
        <div class="mt-4 text-center">
            <strong>إجمالي التقييمات:</strong> <?= $averageScores['total_evaluations'] ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- التقييمات السابقة -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <h2 class="text-xl font-bold p-6 bg-gray-50">التقييمات السابقة</h2>
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

    <div class="mt-6">
        <a href="/employee-portal/public/manager/evaluations" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            العودة إلى قائمة التقييمات
        </a>
    </div>
</div>