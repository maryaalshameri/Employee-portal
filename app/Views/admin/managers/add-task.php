<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">إضافة مهمة للمدير: <?= htmlspecialchars($manager['name']) ?></h2>
    
    <form action="/employee-portal/public/admin/managers/task/<?= $manager['id'] ?>" method="POST">
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان المهمة</label>
            <input type="text" name="title" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">وصف المهمة</label>
            <textarea name="description" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستحقاق</label>
                <input type="date" name="due_date" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="low">منخفضة</option>
                    <option value="medium" selected>متوسطة</option>
                    <option value="high">عالية</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="/employee-portal/public/admin/managers" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                إلغاء
            </a>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                إضافة المهمة
            </button>
        </div>
    </form>
</div>