<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">طلب إجازة - المدير</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="/employee-portal/public/manager/leave-request" method="POST" id="leaveForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                    <input type="date" name="start_date" id="start_date" required 
                           class="w-full px-3 py-2 border rounded-md" 
                           min="<?= date('Y-m-d') ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                    <input type="date" name="end_date" id="end_date" required 
                           class="w-full px-3 py-2 border rounded-md"
                           min="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">عدد الأيام</label>
                <div id="days_calculated" class="bg-gray-100 p-3 rounded text-center text-lg font-bold">
                    0 يوم
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الإجازة</label>
                    <select name="type" required class="w-full px-3 py-2 border rounded-md">
                        <option value="">اختر نوع الإجازة</option>
                        <option value="annual">إجازة سنوية</option>
                        <option value="sick">إجازة مرضية</option>
                        <option value="emergency">إجازة طارئة</option>
                        <option value="other">إجازة أخرى</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رصيد الإجازات المتاح</label>
                    <div class="bg-blue-100 p-3 rounded text-center">
                        <span class="text-xl font-bold text-blue-600"><?= $manager['leaveBalance'] ?></span>
                        <span class="text-sm">يوم متاح</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">سبب الإجازة</label>
                <textarea name="reason" rows="4" required 
                          class="w-full px-3 py-2 border rounded-md" 
                          placeholder="يرجى كتابة سبب طلب الإجازة..."></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="/employee-portal/public/manager/dashboard" 
                   class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    رجوع
                </a>
                
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    تقديم طلب الإجازة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const daysCalculated = document.getElementById('days_calculated');
    
    function calculateDays() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (start > end) {
                daysCalculated.innerHTML = '<span class="text-red-600">تاريخ البداية يجب أن يكون قبل النهاية</span>';
                return;
            }
            
            // حساب الفرق بالأيام (بما في ذلك اليومين)
            const timeDiff = end.getTime() - start.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            
            daysCalculated.innerHTML = `${daysDiff} يوم`;
            
            // التحقق من الرصيد
            const availableBalance = <?= $manager['leaveBalance'] ?>;
            if (daysDiff > availableBalance) {
                daysCalculated.innerHTML += '<br><span class="text-red-600 text-sm">رصيد غير كافي</span>';
            }
        }
    }
    
    startDate.addEventListener('change', calculateDays);
    endDate.addEventListener('change', calculateDays);
    
    // منع التواريخ السابقة
    const today = new Date().toISOString().split('T')[0];
    startDate.min = today;
    endDate.min = today;
});
</script>