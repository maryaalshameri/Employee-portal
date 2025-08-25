<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">تقييمات موظفي قسم <?= htmlspecialchars($manager['department']) ?></h1>

    <!-- نموذج إضافة تقييم -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-bold mb-4">إضافة تقييم جديد</h2>
        <form action="/employee-portal/public/manager/evaluations/create" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الموظف</label>
                    <select name="employee_id" required class="w-full px-3 py-2 border rounded-md">
                        <option value="">اختر الموظف</option>
                        <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?> - <?= htmlspecialchars($employee['position']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ التقييم</label>
                    <input type="date" name="evaluation_date" value="<?= date('Y-m-d') ?>" required class="w-full px-3 py-2 border rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- أداء العمل -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">أداء العمل (1-5)</label>
                    <select name="performance_score" required class="w-full px-3 py-2 border rounded-md">
                        <option value="1">1 - ضعيف</option>
                        <option value="2">2 - مقبول</option>
                        <option value="3">3 - جيد</option>
                        <option value="4">4 - جيد جداً</option>
                        <option value="5">5 - ممتاز</option>
                    </select>
                </div>

                <!-- جودة العمل -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">جودة العمل (1-5)</label>
                    <select name="quality_score" required class="w-full px-3 py-2 border rounded-md">
                        <option value="1">1 - ضعيف</option>
                        <option value="2">2 - مقبول</option>
                        <option value="3">3 - جيد</option>
                        <option value="4">4 - جيد جداً</option>
                        <option value="5">5 - ممتاز</option>
                    </select>
                </div>

                <!-- الالتزام بالمواعيد -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الالتزام بالمواعيد (1-5)</label>
                    <select name="punctuality_score" required class="w-full px-3 py-2 border rounded-md">
                        <option value="1">1 - ضعيف</option>
                        <option value="2">2 - مقبول</option>
                        <option value="3">3 - جيد</option>
                        <option value="4">4 - جيد جداً</option>
                        <option value="5">5 - ممتاز</option>
                    </select>
                </div>

                <!-- العمل الجماعي -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العمل الجماعي (1-5)</label>
                    <select name="teamwork_score" required class="w-full px-3 py-2 border rounded-md">
                        <option value="1">1 - ضعيف</option>
                        <option value="2">2 - مقبول</option>
                        <option value="3">3 - جيد</option>
                        <option value="4">4 - جيد جداً</option>
                        <option value="5">5 - ممتاز</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">التعليقات والملاحظات</label>
                <textarea name="comments" rows="3" class="w-full px-3 py-2 border rounded-md" placeholder="ملاحظات إضافية حول أداء الموظف..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ التقييم القادم (اختياري)</label>
                <input type="date" name="next_evaluation_date" class="w-full px-3 py-2 border rounded-md">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                حفظ التقييم
            </button>
        </form>
    </div>

    <!-- قائمة التقييمات -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التقييم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعدل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التقييم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المقيّم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
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
                        <div class="font-medium"><?= htmlspecialchars($eval['employee_name']) ?></div>
                        <div class="text-sm text-gray-500"><?= htmlspecialchars($eval['position']) ?></div>
                    </td>
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
                        <a href="/employee-portal/public/manager/evaluation-report/<?= $eval['employee_id'] ?>" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            التقرير الكامل
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>