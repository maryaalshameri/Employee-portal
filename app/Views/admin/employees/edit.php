<h1>تعديل بيانات الموظف</h1>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الموظف - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --sidebar-bg: linear-gradient(to bottom, #1e40af, #1e3a8a);
            --hover-effect: translateX(-5px);
        }

        [data-theme="dark"] {
            --primary-color: #60a5fa;
            --secondary-color: #3b82f6;
            --text-color: #e5e7eb;
            --bg-color: #111827;
            --card-bg: #1f2937;
            --sidebar-bg: linear-gradient(to bottom, #111827, #0f172a);
            --hover-effect: translateX(-5px);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .form-container {
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .form-input {
            background-color: var(--card-bg);
            border: 1px solid #d1d5db;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background-color: #ef4444;
            width: 25%;
        }

        .strength-medium {
            background-color: #f59e0b;
            width: 50%;
        }

        .strength-strong {
            background-color: #10b981;
            width: 100%;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="flex h-screen text-gray-800">
    <!-- Main Content -->
    <main class="flex-1 overflow-auto bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
            <div class="header-content flex flex-col md:flex-row md:items-center">
                <h1 class="text-2xl font-bold text-gray-700">
                    تعديل بيانات الموظف
                </h1>
                <p class="text-blue-600 md:mr-4">لوحة إدارة الموظفين</p>
            </div>
            <div class="flex items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold">
                        A
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-6">
            <!-- رسائل التنبيه -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">خطأ!</strong>
                    <span class="block sm:inline"><?= $_SESSION['error_message'] ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>إغلاق</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">نجاح!</strong>
                    <span class="block sm:inline"><?= $_SESSION['success_message'] ?></span>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- نموذج تعديل بيانات الموظف -->
            <div class="form-container p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">تعديل بيانات الموظف: <?= htmlspecialchars($employee['name']) ?></h2>
                
                <form method="post" action="/employee-portal/public/admin/employees/edit/<?= htmlspecialchars($employee['id']) ?>" id="employeeForm" class="space-y-6" novalidate>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- بيانات المستخدم -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-blue-600">بيانات الدخول</h3>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل *</label>
                                <input type="text" id="name" name="name" value="<?= isset($_SESSION['old_input']['name']) ? htmlspecialchars($_SESSION['old_input']['name']) : htmlspecialchars($employee['name']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg" required
                                    pattern="[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\s]{2,50}" 
                                    title="يجب أن يحتوي الاسم على أحرف عربية فقط (من 2 إلى 50 حرف)">
                                <?php if (isset($_SESSION['errors']['name'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['name'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">يجب أن يحتوي على أحرف عربية فقط (من 2 إلى 50 حرف)</p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني *</label>
                                <input type="email" id="email" name="email" value="<?= isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : htmlspecialchars($employee['email']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg" required>
                                <?php if (isset($_SESSION['errors']['email'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['email'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور (اتركه فارغاً إذا لم ترد التغيير)</label>
                                <input type="password" id="password" name="password" 
                                    class="form-input w-full px-4 py-2 rounded-lg"
                                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$"
                                    title="يجب أن تحتوي كلمة المرور على الأقل على 8 أحرف، حرف واحد ورقم واحد على الأقل">
                                <div id="passwordStrength" class="password-strength"></div>
                                <?php if (isset($_SESSION['errors']['password'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['password'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">اتركه فارغاً إذا لم ترد التغيير. يجب أن تحتوي على الأقل على 8 أحرف، حرف واحد ورقم واحد على الأقل</p>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                                <input type="password" id="confirm_password" name="confirm_password" 
                                    class="form-input w-full px-4 py-2 rounded-lg">
                                <p id="passwordMatch" class="text-xs mt-1"></p>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">الدور *</label>
                                <select id="role" name="role" class="form-input w-full px-4 py-2 rounded-lg" required>
                                    <option value="employee" <?= ((isset($_SESSION['old_input']['role']) ? $_SESSION['old_input']['role'] : $employee['role']) == 'employee') ? 'selected' : '' ?>>موظف</option>
                                    <option value="manager" <?= ((isset($_SESSION['old_input']['role']) ? $_SESSION['old_input']['role'] : $employee['role']) == 'manager') ? 'selected' : '' ?>>مدير</option>
                                </select>
                                <?php if (isset($_SESSION['errors']['role'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['role'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- بيانات الموظف -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-blue-600">البيانات الوظيفية</h3>
                            
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">القسم *</label>
                                <select id="department" name="department" class="form-input w-full px-4 py-2 rounded-lg" required>
                                    <option value="الموارد البشرية" <?= ((isset($_SESSION['old_input']['department']) ? $_SESSION['old_input']['department'] : $employee['department']) == 'الموارد البشرية') ? 'selected' : '' ?>>الموارد البشرية</option>
                                    <option value="المشاريع" <?= ((isset($_SESSION['old_input']['department']) ? $_SESSION['old_input']['department'] : $employee['department']) == 'المشاريع') ? 'selected' : '' ?>>المشاريع</option>
                                    <option value="المالية" <?= ((isset($_SESSION['old_input']['department']) ? $_SESSION['old_input']['department'] : $employee['department']) == 'المالية') ? 'selected' : '' ?>>المالية</option>
                                    <option value="developer" <?= ((isset($_SESSION['old_input']['department']) ? $_SESSION['old_input']['department'] : $employee['department']) == 'developer') ? 'selected' : '' ?>>Developer</option>
                                </select>
                                <?php if (isset($_SESSION['errors']['department'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['department'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">الوظيفة *</label>
                                <input type="text" id="position" name="position" value="<?= isset($_SESSION['old_input']['position']) ? htmlspecialchars($_SESSION['old_input']['position']) : htmlspecialchars($employee['position']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg" required
                                    pattern="[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\s]{2,50}" 
                                    title="يجب أن تحتوي الوظيفة على أحرف عربية فقط (من 2 إلى 50 حرف)">
                                <?php if (isset($_SESSION['errors']['position'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['position'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">يجب أن تحتوي على أحرف عربية فقط (من 2 إلى 50 حرف)</p>
                            </div>

                            <div>
                                <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ التعيين *</label>
                                <input type="date" id="hire_date" name="hire_date" value="<?= isset($_SESSION['old_input']['hire_date']) ? htmlspecialchars($_SESSION['old_input']['hire_date']) : htmlspecialchars($employee['hire_date']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg" required
                                    max="<?= date('Y-m-d') ?>">
                                <?php if (isset($_SESSION['errors']['hire_date'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['hire_date'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">يجب أن لا يتجاوز تاريخ اليوم</p>
                            </div>

                            <div>
                                <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">الراتب *</label>
                                <input type="number" step="0.01" id="salary" name="salary" value="<?= isset($_SESSION['old_input']['salary']) ? htmlspecialchars($_SESSION['old_input']['salary']) : htmlspecialchars($employee['salary']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg" required
                                    min="0" 
                                    title="يجب أن يكون الراتب رقم موجب">
                                <?php if (isset($_SESSION['errors']['salary'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['salary'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">يجب أن يكون الراتب رقم موجب</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- معلومات الاتصال -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-blue-600">معلومات الاتصال</h3>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                                <input type="tel" id="phone" name="phone" value="<?= isset($_SESSION['old_input']['phone']) ? htmlspecialchars($_SESSION['old_input']['phone']) : htmlspecialchars($employee['phone']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg">
                                <input type="hidden" id="phone_country" name="phone_country" value="<?= isset($_SESSION['old_input']['phone_country']) ? htmlspecialchars($_SESSION['old_input']['phone_country']) : 'sa' ?>">
                                <?php if (isset($_SESSION['errors']['phone'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['phone'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">يجب أن يكون رقم هاتف صحيح</p>
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                                <textarea id="address" name="address" rows="3" class="form-input w-full px-4 py-2 rounded-lg"><?= isset($_SESSION['old_input']['address']) ? htmlspecialchars($_SESSION['old_input']['address']) : htmlspecialchars($employee['address']) ?></textarea>
                                <?php if (isset($_SESSION['errors']['address'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['address'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-blue-600">معلومات إضافية</h3>
                            
                            <div>
                                <label for="work_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الدوام</label>
                                <select id="work_type" name="work_type" class="form-input w-full px-4 py-2 rounded-lg">
                                    <option value="full-time" <?= ((isset($_SESSION['old_input']['work_type']) ? $_SESSION['old_input']['work_type'] : $employee['work_type']) == 'full-time') ? 'selected' : '' ?>>دوام كامل</option>
                                    <option value="part-time" <?= ((isset($_SESSION['old_input']['work_type']) ? $_SESSION['old_input']['work_type'] : $employee['work_type']) == 'part-time') ? 'selected' : '' ?>>دوام جزئي</option>
                                    <option value="freelance" <?= ((isset($_SESSION['old_input']['work_type']) ? $_SESSION['old_input']['work_type'] : $employee['work_type']) == 'freelance') ? 'selected' : '' ?>>عمل حر</option>
                                </select>
                                <?php if (isset($_SESSION['errors']['work_type'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['work_type'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label for="leaveBalance" class="block text-sm font-medium text-gray-700 mb-1">رصيد الإجازات *</label>
                                <input type="number" id="leaveBalance" name="leaveBalance" value="<?= isset($_SESSION['old_input']['leaveBalance']) ? htmlspecialchars($_SESSION['old_input']['leaveBalance']) : htmlspecialchars($employee['leaveBalance']) ?>" 
                                    class="form-input w-full px-4 py-2 rounded-lg" required
                                    min="0" max="60" 
                                    title="يجب أن يكون رصيد الإجازات بين 0 و 60 يوم">
                                <?php if (isset($_SESSION['errors']['leaveBalance'])): ?>
                                    <p class="error-message"><?= $_SESSION['errors']['leaveBalance'] ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-500 mt-1">يجب أن يكون بين 0 و 60 يوم</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t gap-5">
                        <a href="/employee-portal/public/admin/employees" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                            إلغاء
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            تحديث البيانات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تهيئة مدخل الهاتف مع تحديد رمز الدولة
            const phoneInput = document.querySelector("#phone");
            const phoneCountry = document.getElementById("phone_country").value || "sa";
            
            const phoneIti = window.intlTelInput(phoneInput, {
                initialCountry: phoneCountry,
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            // عند تغيير الدولة، تحديث الحقل المخفي
            phoneInput.addEventListener("countrychange", function() {
                document.getElementById("phone_country").value = phoneIti.getSelectedCountryData().iso2;
            });

            // التحقق من قوة كلمة المرور
            const passwordInput = document.getElementById('password');
            const passwordStrength = document.getElementById('passwordStrength');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordMatch = document.getElementById('passwordMatch');

            passwordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                let strength = '';

                if (password.length === 0) {
                    strength = '';
                    passwordStrength.className = 'password-strength';
                } else if (password.length < 6) {
                    strength = 'ضعيفة';
                    passwordStrength.className = 'password-strength strength-weak';
                } else if (password.length < 8 || !/(?=.*[a-zA-Z])(?=.*[0-9])/.test(password)) {
                    strength = 'متوسطة';
                    passwordStrength.className = 'password-strength strength-medium';
                } else {
                    strength = 'قوية';
                    passwordStrength.className = 'password-strength strength-strong';
                }

                if (strength) {
                    passwordStrength.textContent = `قوة كلمة المرور: ${strength}`;
                }
            });

            // التحقق من تطابق كلمة المرور
            confirmPasswordInput.addEventListener('input', function() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    passwordMatch.textContent = 'كلمة المرور غير متطابقة';
                    passwordMatch.className = 'error-message';
                } else {
                    passwordMatch.textContent = 'كلمة المرور متطابقة';
                    passwordMatch.className = 'success-message';
                }
            });

            // التحقق من صحة النموذج قبل الإرسال
            document.getElementById('employeeForm').addEventListener('submit', function(e) {
                let isValid = true;
                
                // التحقق من صحة الهاتف
                if (phoneInput.value && !phoneIti.isValidNumber()) {
                    alert('يرجى إدخال رقم هاتف صحيح');
                    isValid = false;
                }
                
                // التحقق من تطابق كلمة المرور إذا تم إدخالها
                if (passwordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                    alert('كلمة المرور غير متطابقة');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>

<?php
// تنظيف جلسة الأخطاء والقيم القديمة بعد عرضها
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}
?>