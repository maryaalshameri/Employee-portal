<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - الموظف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --gradient-start: #3498db;
            --gradient-end: #2c3e50;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            padding: 2rem;
            color: white;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary-color);
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .rating-stars {
            color: #ffc107;
            font-size: 1.2rem;
        }

        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .accordion-item {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .accordion-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .accordion-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .accordion-header:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }

        .accordion-content.open {
            max-height: 500px;
        }

        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }

        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(52, 152, 219, 0.4);
        }

        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .floating-input {
            width: 100%;
            padding: 1rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            transition: all 0.3s ease;
        }

        .floating-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .floating-label-text {
            position: absolute;
            right: 1rem;
            top: 1rem;
            background: white;
            padding: 0 0.5rem;
            color: #64748b;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .floating-input:focus ~ .floating-label-text,
        .floating-input:not(:placeholder-shown) ~ .floating-label-text {
            top: -0.5rem;
            font-size: 0.875rem;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="py-8 px-4">
    <div class="container mx-auto max-w-6xl">
        <!-- رسائل التنبيه -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- البطاقة الرئيسية -->
        <div class="profile-card mb-8">
            <div class="profile-header text-center">
                <div class="profile-avatar pulse">
                    <?= strtoupper(substr($employee['name'], 0, 1)) ?>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mt-4"><?= htmlspecialchars($employee['name']) ?></h1>
                <p class="text-blue-100"><?= htmlspecialchars($employee['position']) ?> - <?= htmlspecialchars($employee['department']) ?></p>
                <div class="absolute top-4 left-4">
                    <span class="badge bg-white text-blue-700">
                        <i class="fas fa-user ml-1"></i>
                        موظف
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- المعلومات الأساسية -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-user-circle ml-2 text-blue-500"></i>
                        المعلومات الأساسية
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 p-2 rounded-full ml-3">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                                <p class="font-medium"><?= htmlspecialchars($employee['email']) ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="bg-purple-100 p-2 rounded-full ml-3">
                                <i class="fas fa-briefcase text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">القسم</p>
                                <p class="font-medium"><?= htmlspecialchars($employee['department']) ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="bg-green-100 p-2 rounded-full ml-3">
                                <i class="fas fa-user-tie text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">الوظيفة</p>
                                <p class="font-medium"><?= htmlspecialchars($employee['position']) ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="bg-yellow-100 p-2 rounded-full ml-3">
                                <i class="fas fa-shield-alt text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">الدور</p>
                                <p class="font-medium"><?= htmlspecialchars($employee['role']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الإحصائيات -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-chart-pie ml-2 text-blue-500"></i>
                        إحصائيات الأداء
                    </h2>
                    
                    <?php if (!empty($evaluations)): ?>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="stats-card text-center">
                            <div class="text-3xl font-bold text-blue-600 mb-2"><?= number_format($averageScores['overall_avg'] ?? 0, 1) ?></div>
                            <div class="rating-stars mb-2">
                                <?php
                                $overallAvg = $averageScores['overall_avg'] ?? 0;
                                for ($i = 1; $i <= 5; $i++): 
                                    $starClass = $i <= $overallAvg ? 'fas fa-star' : 'far fa-star';
                                ?>
                                    <i class="<?= $starClass ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm text-gray-600">المعدل العام</p>
                        </div>
                        
                        <div class="stats-card text-center">
                            <div class="text-3xl font-bold text-purple-600 mb-2"><?= $averageScores['total_evaluations'] ?? 0 ?></div>
                            <i class="fas fa-clipboard-check text-purple-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">عدد التقييمات</p>
                        </div>
                    </div>
                    
                    <div class="stats-card">
                        <h3 class="font-semibold mb-4 text-gray-700">التقييمات التفصيلية</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">أداء العمل</span>
                                    <span class="text-sm font-semibold"><?= number_format($averageScores['avg_performance'] ?? 0, 1) ?>/5</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-blue-500" style="width: <?= ($averageScores['avg_performance'] ?? 0) * 20 ?>%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">جودة العمل</span>
                                    <span class="text-sm font-semibold"><?= number_format($averageScores['avg_quality'] ?? 0, 1) ?>/5</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-green-500" style="width: <?= ($averageScores['avg_quality'] ?? 0) * 20 ?>%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">الالتزام بالمواعيد</span>
                                    <span class="text-sm font-semibold"><?= number_format($averageScores['avg_punctuality'] ?? 0, 1) ?>/5</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-purple-500" style="width: <?= ($averageScores['avg_punctuality'] ?? 0) * 20 ?>%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600">العمل الجماعي</span>
                                    <span class="text-sm font-semibold"><?= number_format($averageScores['avg_teamwork'] ?? 0, 1) ?>/5</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-orange-500" style="width: <?= ($averageScores['avg_teamwork'] ?? 0) * 20 ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                        <h3 class="text-gray-500">لا توجد تقييمات حتى الآن</h3>
                        <p class="text-gray-400">سيظهر هنا تقييمات أدائك عند توفرها</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- المعلومات الإضافية -->
            <div class="profile-card p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle ml-2 text-blue-500"></i>
                    المعلومات الإضافية
                </h2>
                
                <form action="/employee-portal/public/employee/profile/update" method="POST" class="space-y-4">
                    <div class="floating-label">
                        <input type="tel" name="phone" class="floating-input" placeholder=" " 
                               value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
                        <label class="floating-label-text">رقم الهاتف</label>
                    </div>
                    
                    <div class="floating-label">
                        <textarea name="address" class="floating-input" placeholder=" " rows="3"><?= htmlspecialchars($employee['address'] ?? '') ?></textarea>
                        <label class="floating-label-text">العنوان</label>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">نوع العمل</p>
                            <p class="font-medium"><?= htmlspecialchars($employee['work_type']) ?></p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">رصيد الإجازات</p>
                            <p class="font-medium"><?= $employee['leaveBalance'] ?> يوم</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">تاريخ التعيين</p>
                            <p class="font-medium"><?= $employee['hire_date'] ?></p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">الراتب الأساسي</p>
                            <p class="font-medium"><?= number_format($employee['salary'], 2) ?> ر.ي</p>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-medium w-full flex items-center justify-center">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التعديلات
                    </button>
                </form>
            </div>

            <!-- التقييمات التفصيلية -->
            <div class="profile-card p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-star ml-2 text-blue-500"></i>
                    التقييمات التفصيلية
                </h2>
                
                <?php if (!empty($evaluations)): ?>
                <div class="space-y-4">
                    <?php foreach ($evaluations as $eval): ?>
                    <?php
                    $avgScore = ($eval['performance_score'] + $eval['quality_score'] + 
                               $eval['punctuality_score'] + $eval['teamwork_score']) / 4;
                    $ratingColor = $avgScore >= 4 ? 'text-green-600' : 
                                  ($avgScore >= 3 ? 'text-yellow-600' : 'text-red-600');
                    $badgeColor = $avgScore >= 4 ? 'bg-green-100 text-green-800' : 
                                  ($avgScore >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                    ?>
                    <div class="accordion-item">
                        <div class="accordion-header flex items-center justify-between" onclick="toggleAccordion(this)">
                            <div class="flex items-center">
                                <span class="<?= $ratingColor ?> font-bold text-lg ml-2">
                                    <?= number_format($avgScore, 1) ?>
                                </span>
                                <div class="rating-stars ml-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $avgScore ? 'fas fa-star' : 'far fa-star' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-sm text-gray-500 mr-2"><?= $eval['evaluation_date'] ?></span>
                            </div>
                            <div class="flex items-center">
                                <span class="badge <?= $badgeColor ?> mr-2">
                                    <?= $avgScore >= 4 ? 'ممتاز' : ($avgScore >= 3 ? 'جيد' : 'يحتاج تحسين') ?>
                                </span>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                            </div>
                        </div>
                        
                        <div class="accordion-content">
                            <div class="p-4 bg-white">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <p class="text-sm text-blue-600">أداء العمل</p>
                                        <p class="font-bold text-blue-800"><?= $eval['performance_score'] ?>/5</p>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <p class="text-sm text-green-600">جودة العمل</p>
                                        <p class="font-bold text-green-800"><?= $eval['quality_score'] ?>/5</p>
                                    </div>
                                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                                        <p class="text-sm text-purple-600">الالتزام بالمواعيد</p>
                                        <p class="font-bold text-purple-800"><?= $eval['punctuality_score'] ?>/5</p>
                                    </div>
                                    <div class="text-center p-3 bg-orange-50 rounded-lg">
                                        <p class="text-sm text-orange-600">العمل الجماعي</p>
                                        <p class="font-bold text-orange-800"><?= $eval['teamwork_score'] ?>/5</p>
                                    </div>
                                </div>
                                
                                <?php if (!empty($eval['comments'])): ?>
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-500 mb-1">ملاحظات المقيّم:</p>
                                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($eval['comments'])) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>المقيّم: <?= htmlspecialchars($eval['evaluator_name']) ?></span>
                                    <?php if (!empty($eval['next_evaluation_date'])): ?>
                                    <span>التقييم القادم: <?= $eval['next_evaluation_date'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-star fa-3x text-gray-300 mb-3"></i>
                    <h3 class="text-gray-500">لا توجد تقييمات حتى الآن</h3>
                    <p class="text-gray-400">سيظهر هنا تقييمات أدائك عند توفرها</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // تفعيل الأكورديون
        function toggleAccordion(element) {
            const content = element.nextElementSibling;
            const icon = element.querySelector('.fa-chevron-down');
            
            content.classList.toggle('open');
            icon.classList.toggle('rotate-180');
            
            // إغلاق الباقي
            const allItems = document.querySelectorAll('.accordion-item');
            allItems.forEach(item => {
                if (item !== element.parentElement) {
                    item.querySelector('.accordion-content').classList.remove('open');
                    item.querySelector('.fa-chevron-down').classList.remove('rotate-180');
                }
            });
        }

        // تفعيل تأثيرات التحميل للشريط التقدمي
        document.addEventListener('DOMContentLoaded', function() {
            const progressFills = document.querySelectorAll('.progress-fill');
            
            setTimeout(() => {
                progressFills.forEach(fill => {
                    const width = fill.style.width;
                    fill.style.width = '0';
                    setTimeout(() => {
                        fill.style.width = width;
                    }, 100);
                });
            }, 500);
        });

        // إضافة class للتدوير
        const style = document.createElement('style');
        style.textContent = `
            .rotate-180 {
                transform: rotate(180deg);
            }
            .fa-chevron-down {
                transition: transform 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>