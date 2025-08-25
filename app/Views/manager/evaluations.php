<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييمات الموظفين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .star-rating {
            display: inline-flex;
            direction: ltr;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            color: #ddd;
            cursor: pointer;
            font-size: 1.5rem;
            transition: color 0.2s;
        }
        
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }
        
        .rating-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .evaluation-row {
            transition: all 0.3s ease;
        }
        
        .evaluation-row:hover {
            background-color: #f8fafc;
            transform: scale(1.01);
        }
        
        .score-cell {
            min-width: 120px;
        }
        
        .score-bar {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
        }
        
        .score-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(102, 126, 234, 0.4);
        }
        
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            
            .evaluation-table {
                min-width: 800px;
            }
        }
        
        .animate-pulse {
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
        <!-- العنوان الرئيسي -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">تقييمات موظفي قسم <?= htmlspecialchars($manager['department']) ?></h1>
            <p class="text-gray-600">إدارة وتتبع أداء موظفي القسم بشكل فعال</p>
        </div>

        <!-- نموذج إضافة تقييم -->
        <div class="card p-6 mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-plus-circle ml-2 text-blue-500"></i>
                    إضافة تقييم جديد
                </h2>
                <div class="animate-pulse">
                    <i class="fas fa-star text-yellow-400 text-xl"></i>
                </div>
            </div>
            
            <form action="/employee-portal/public/manager/evaluations/create" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الموظف</label>
                        <div class="relative">
                            <select name="employee_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                                <option value="">اختر الموظف</option>
                                <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?> - <?= htmlspecialchars($employee['position']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التقييم</label>
                        <div class="relative">
                            <input type="date" name="evaluation_date" value="<?= date('Y-m-d') ?>" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- أداء العمل -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">أداء العمل</label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="performance-<?= $i ?>" name="performance_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                            <label for="performance-<?= $i ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                    </div>

                    <!-- جودة العمل -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">جودة العمل</label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="quality-<?= $i ?>" name="quality_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                            <label for="quality-<?= $i ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                    </div>

                    <!-- الالتزام بالمواعيد -->
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الالتزام بالمواعيد</label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="punctuality-<?= $i ?>" name="punctuality_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                            <label for="punctuality-<?= $i ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                    </div>

                    <!-- العمل الجماعي -->
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">العمل الجماعي</label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="teamwork-<?= $i ?>" name="teamwork_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                            <label for="teamwork-<?= $i ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">التعليقات والملاحظات</label>
                    <textarea name="comments" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="أدخل ملاحظاتك حول أداء الموظف..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التقييم القادم (اختياري)</label>
                        <div class="relative">
                            <input type="date" name="next_evaluation_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-medium w-full flex items-center justify-center">
                            <i class="fas fa-save ml-2"></i>
                            حفظ التقييم
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- قائمة التقييمات -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list-alt ml-2 text-blue-500"></i>
                    قائمة التقييمات
                </h2>
            </div>
            
            <div class="table-container">
                <table class="w-full evaluation-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الموظف</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">التقييم</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">المعدل</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">تاريخ التقييم</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">المقيّم</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($evaluations as $eval): ?>
                        <?php
                        $averageScore = ($eval['performance_score'] + $eval['quality_score'] + 
                                       $eval['punctuality_score'] + $eval['teamwork_score']) / 4;
                        $ratingColor = $averageScore >= 4 ? 'bg-green-100 text-green-800' : 
                                      ($averageScore >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                        ?>
                        <tr class="evaluation-row">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($eval['employee_name']) ?></div>
                                <div class="text-sm text-gray-500"><?= htmlspecialchars($eval['position']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="text-sm">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">أداء:</span>
                                            <span class="font-medium"><?= $eval['performance_score'] ?>/5</span>
                                        </div>
                                        <div class="score-bar">
                                            <div class="score-fill bg-blue-500" style="width: <?= $eval['performance_score'] * 20 ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="text-sm">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">جودة:</span>
                                            <span class="font-medium"><?= $eval['quality_score'] ?>/5</span>
                                        </div>
                                        <div class="score-bar">
                                            <div class="score-fill bg-green-500" style="width: <?= $eval['quality_score'] * 20 ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="text-sm">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">مواعيد:</span>
                                            <span class="font-medium"><?= $eval['punctuality_score'] ?>/5</span>
                                        </div>
                                        <div class="score-bar">
                                            <div class="score-fill bg-purple-500" style="width: <?= $eval['punctuality_score'] * 20 ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="text-sm">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">فريق:</span>
                                            <span class="font-medium"><?= $eval['teamwork_score'] ?>/5</span>
                                        </div>
                                        <div class="score-bar">
                                            <div class="score-fill bg-orange-500" style="width: <?= $eval['teamwork_score'] * 20 ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold <?= str_replace('bg-', 'text-', $ratingColor) ?>">
                                        <?= number_format($averageScore, 1) ?>
                                    </span>
                                    <div class="text-yellow-400 text-lg">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= round($averageScore) ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-badge <?= $ratingColor ?> mt-1">
                                        <?= $averageScore >= 4 ? 'ممتاز' : ($averageScore >= 3 ? 'جيد' : 'يحتاج تحسين') ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $eval['evaluation_date'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($eval['evaluator_name']) ?></td>
                            <td class="px-6 py-4">
                                <a href="/employee-portal/public/manager/evaluation-report/<?= $eval['employee_id'] ?>" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-chart-line ml-1"></i>
                                    التقرير الكامل
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // تفعيل تأثيرات التحميل للشريط التقدمي
        document.addEventListener('DOMContentLoaded', function() {
            const scoreFills = document.querySelectorAll('.score-fill');
            
            setTimeout(() => {
                scoreFills.forEach(fill => {
                    const width = fill.style.width;
                    fill.style.width = '0';
                    setTimeout(() => {
                        fill.style.width = width;
                    }, 100);
                });
            }, 500);
        });
    </script>
</body>
</html>