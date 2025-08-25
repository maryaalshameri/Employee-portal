<?php
// تعريف متغيرات افتراضية لتجنب الأخطاء
$employees = $employees ?? [];
$employeeSalaries = $employeeSalaries ?? [];
?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b-2 border-blue-200 pb-3">إضافة راتب جديد</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form action="/employee-portal/public/admin/salaries/create" method="POST" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">الموظف *</label>
                <select name="employee_id" id="employee_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="updateBaseSalary()">
                    <option value="">اختر الموظف</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee['id'] ?>" 
                                data-salary="<?= $employeeSalaries[$employee['id']] ?? 0 ?>"
                                <?= isset($_POST['employee_id']) && $_POST['employee_id'] == $employee['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['name']) ?> - 
                            <?= htmlspecialchars($employee['position']) ?> -
                            <?= number_format($employeeSalaries[$employee['id']] ?? 0, 2) ?> ر.س
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="base_salary" class="block text-sm font-medium text-gray-700 mb-1">الراتب الأساسي (من جدول الموظفين)</label>
                <input type="text" id="base_salary" readonly
                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md"
                       value="0.00 ر.س">
                <input type="hidden" id="base_salary_value" value="0">
            </div>
            
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">الراتب الأساسي للتعديل *</label>
                <input type="number" name="amount" id="amount" step="0.01" required 
                       value="<?= $_POST['amount'] ?? '' ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="0.00"
                       oninput="calculateNetSalary()">
            </div>
            
            <div>
                <label for="bonusPercentage" class="block text-sm font-medium text-gray-700 mb-1">نسبة المكافأة %</label>
                <input type="number" name="bonusPercentage" id="bonusPercentage" step="0.01" 
                       value="<?= $_POST['bonusPercentage'] ?? '0' ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="0"
                       oninput="calculateNetSalary()">
            </div>
            
            <div>
                <label for="deductionPercentage" class="block text-sm font-medium text-gray-700 mb-1">نسبة الخصم %</label>
                <input type="number" name="deductionPercentage" id="deductionPercentage" step="0.01" 
                       value="<?= $_POST['deductionPercentage'] ?? '0' ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="0"
                       oninput="calculateNetSalary()">
            </div>
            
            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ الصرف *</label>
                <input type="date" name="payment_date" id="payment_date" required 
                       value="<?= $_POST['payment_date'] ?? date('Y-m-d') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="net_salary" class="block text-sm font-medium text-gray-700 mb-1">صافي الراتب</label>
                <div id="net_salary" class="w-full px-3 py-3 bg-gray-100 rounded-md font-bold text-green-600 text-lg">
                    0.00 ر.س
                </div>
            </div>
        </div>
        
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
            <textarea name="notes" id="notes" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="ملاحظات إضافية..."><?= $_POST['comments'] ?? '' ?></textarea>
        </div>
        
        <div class="flex flex-col gap-5 space-x-3 pt-4 md:flex-row">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                <span class="material-icons mr-2">save</span>
                حفظ الراتب
            </button>
            <a href="/employee-portal/public/admin/salaries" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                <span class="material-icons mr-2">cancel</span>
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تعيين تاريخ اليوم كقيمة افتراضية
    document.getElementById('payment_date').valueAsDate = new Date();
    
    // حساب أولي عند تحميل الصفحة
    calculateNetSalary();
});

// دالة تحديث الراتب الأساسي عند اختيار موظف
function updateBaseSalary() {
    const employeeSelect = document.getElementById('employee_id');
    const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
    const baseSalary = selectedOption.getAttribute('data-salary') || 0;
    
    // تحديث الحقل المعروض
    document.getElementById('base_salary').value = parseFloat(baseSalary).toFixed(2) + ' ر.س';
    document.getElementById('base_salary_value').value = baseSalary;
    
    // تحديث حقل الراتب الأساسي للتعديل
    document.getElementById('amount').value = baseSalary;
    
    // إعادة حساب صافي الراتب
    calculateNetSalary();
}

// دالة حساب صافي الراتب
function calculateNetSalary() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const bonusPercentage = parseFloat(document.getElementById('bonusPercentage').value) || 0;
    const deductionPercentage = parseFloat(document.getElementById('deductionPercentage').value) || 0;
    
    const bonusAmount = amount * (bonusPercentage / 100);
    const deductionAmount = amount * (deductionPercentage / 100);
    const netSalary = amount + bonusAmount - deductionAmount;
    
    const netSalaryElement = document.getElementById('net_salary');
    netSalaryElement.textContent = netSalary.toFixed(2) + ' ر.س';
    
    // تغيير اللون حسب القيمة
    if (netSalary > amount) {
        netSalaryElement.classList.remove('text-red-600', 'text-gray-600');
        netSalaryElement.classList.add('text-green-600');
    } else if (netSalary < amount) {
        netSalaryElement.classList.remove('text-green-600', 'text-gray-600');
        netSalaryElement.classList.add('text-red-600');
    } else {
        netSalaryElement.classList.remove('text-green-600', 'text-red-600');
        netSalaryElement.classList.add('text-gray-600');
    }
    
    // تحديث تفاصيل الحساب
    updateCalculationDetails(amount, bonusAmount, deductionAmount, netSalary);
}

// دالة لعرض تفاصيل الحساب
function updateCalculationDetails(amount, bonusAmount, deductionAmount, netSalary) {
    const details = `
        <div class="mt-4 p-3 bg-blue-50 rounded-md">
            <h4 class="font-semibold text-blue-800 mb-2">تفاصيل الحساب:</h4>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>الراتب الأساسي:</div>
                <div class="text-right">${amount.toFixed(2)} ر.س</div>
                
                <div>المكافأة (${document.getElementById('bonusPercentage').value}%):</div>
                <div class="text-right text-green-600">+ ${bonusAmount.toFixed(2)} ر.س</div>
                
                <div>الخصم (${document.getElementById('deductionPercentage').value}%):</div>
                <div class="text-right text-red-600">- ${deductionAmount.toFixed(2)} ر.س</div>
                
                <div class="font-bold border-t border-blue-200 pt-1">الصافي:</div>
                <div class="text-right font-bold border-t border-blue-200 pt-1">${netSalary.toFixed(2)} ر.س</div>
            </div>
        </div>
    `;
    
    // إزالة التفاصيل القديمة إذا existed
    const oldDetails = document.getElementById('calculation_details');
    if (oldDetails) {
        oldDetails.remove();
    }
    
    // إضافة التفاصيل الجديدة
    const netSalaryElement = document.getElementById('net_salary');
    const detailsElement = document.createElement('div');
    detailsElement.id = 'calculation_details';
    detailsElement.innerHTML = details;
    netSalaryElement.parentNode.appendChild(detailsElement);
}
</script>