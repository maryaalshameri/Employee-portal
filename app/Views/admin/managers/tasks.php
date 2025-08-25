<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">مهام المدير: <?= htmlspecialchars($manager['name']) ?></h2>

    <!-- قائمة المهام -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <h3 class="text-xl font-bold p-4 bg-gray-50">المهام الحالية</h3>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوصف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الاستحقاق</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
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
                    <td class="px-6 py-4">
                        <form action="/employee-portal/public/admin/managers/update-task/<?= $task['id'] ?>" method="POST" class="inline">
                            <select name="status" onchange="this.form.submit()" class="text-sm border rounded">
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

    <!-- نموذج إضافة مهمة جديدة -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4">إضافة مهمة جديدة</h3>
        <form action="/employee-portal/public/admin/managers/task/<?= $manager['id'] ?>" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">عنوان المهمة</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الاستحقاق</label>
                    <input type="date" name="due_date" class="w-full px-3 py-2 border rounded-md">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الأولوية</label>
                    <select name="priority" class="w-full px-3 py-2 border rounded-md">
                        <option value="low">منخفضة</option>
                        <option value="medium" selected>متوسطة</option>
                        <option value="high">عالية</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-md"></textarea>
            </div>
            
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                إضافة المهمة
            </button>
        </form>
    </div>
</div>