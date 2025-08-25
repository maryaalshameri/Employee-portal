
    
    <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">تقييم المدير: <?= htmlspecialchars($manager['name']) ?></h2>
    
    <?php if ($hasRecentEvaluation): ?>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>ملاحظة:</strong> هذا المدير لديه تقييم حديث خلال آخر 3 أشهر. 
        التقييمات تكون كل 3 أشهر على الأقل.
    </div>
    <?php endif; ?>
    
    <form action="/employee-portal/public/admin/managers/evaluate/<?= $manager['id'] ?>" method="POST">
        <input type="hidden" name="employee_id" value="<?= $manager['id'] ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- أدوات التقييم -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">أداء العمل (1-5)</label>
                    <input type="number" name="performance_score" min="1" max="5" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">جودة العمل (1-5)</label>
                    <input type="number" name="quality_score" min="1" max="5" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الالتزام بالوقت (1-5)</label>
                    <input type="number" name="punctuality_score" min="1" max="5" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">العمل الجماعي (1-5)</label>
                    <input type="number" name="teamwork_score" min="1" max="5" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">التعليقات</label>
            <textarea name="comments" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التقييم</label>
            <input type="date" name="evaluation_date" value="<?= date('Y-m-d') ?>" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">موعد التقييم القادم (اختياري)</label>
            <input type="date" name="next_evaluation_date" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md"
                   min="<?= date('Y-m-d', strtotime('+3 months')) ?>">
            <p class="text-sm text-gray-500 mt-1">سيتم حظر التقييمات الجديدة قبل 3 أشهر من هذا التاريخ</p>
        </div>
        
        <div class="flex flex-col justify-end space-x-3 gap-3 md:flex-row">
            <a href="/employee-portal/public/admin/managers" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                إلغاء
            </a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    <?= $hasRecentEvaluation ? 'disabled' : '' ?>>
                حفظ التقييم
            </button>
        </div>
        
        <?php if ($hasRecentEvaluation): ?>
        <div class="mt-4 text-red-600 text-sm">
            <i class="fas fa-info-circle mr-1"></i>
            لا يمكن إضافة تقييم جديد بسبب وجود تقييم حديث خلال آخر 3 أشهر
        </div>
        <?php endif; ?>
    </form>
</div>