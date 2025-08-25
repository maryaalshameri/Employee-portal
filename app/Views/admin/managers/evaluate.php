<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم المدير</title>
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
            font-size: 1.8rem;
            transition: color 0.2s;
            margin: 0 2px;
        }
        
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }
        
        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }
        
        .input-field:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(16, 185, 129, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(107, 114, 128, 0.4);
        }
        
        .warning-message {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        
        .score-display {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0 auto;
        }
    </style>
</head>
<body class="py-8 px-4">
    <div class="container mx-auto max-w-4xl">
        <!-- العنوان الرئيسي -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">تقييم المدير: <?= htmlspecialchars($manager['name']) ?></h1>
            <p class="text-gray-600">تقييم أداء المدير بشكل دوري لضمان جودة العمل</p>
        </div>

        <div class="card p-6 md:p-8">
            <?php if ($hasRecentEvaluation): ?>
            <div class="warning-message bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-triangle ml-2 text-xl"></i>
                <div>
                    <strong class="block">ملاحظة:</strong> 
                    هذا المدير لديه تقييم حديث خلال آخر 3 أشهر. التقييمات تكون كل 3 أشهر على الأقل.
                </div>
            </div>
            <?php endif; ?>
            
            <form action="/employee-portal/public/admin/managers/evaluate/<?= $manager['id'] ?>" method="POST">
                <input type="hidden" name="employee_id" value="<?= $manager['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- أدوات التقييم -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-5 rounded-xl">
                            <label class="block text-lg font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-tachometer-alt ml-2 text-blue-500"></i>
                                أداء العمل
                            </label>
                            <div class="text-center mb-3">
                                <div class="score-display bg-blue-100 text-blue-800" id="performance-score-display">3</div>
                            </div>
                            <div class="star-rating justify-center">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="performance-<?= $i ?>" name="performance_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                                <label for="performance-<?= $i ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-2 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                        </div>
                        
                        <div class="bg-green-50 p-5 rounded-xl">
                            <label class="block text-lg font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-medal ml-2 text-green-500"></i>
                                جودة العمل
                            </label>
                            <div class="text-center mb-3">
                                <div class="score-display bg-green-100 text-green-800" id="quality-score-display">3</div>
                            </div>
                            <div class="star-rating justify-center">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="quality-<?= $i ?>" name="quality_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                                <label for="quality-<?= $i ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-2 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-purple-50 p-5 rounded-xl">
                            <label class="block text-lg font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-clock ml-2 text-purple-500"></i>
                                الالتزام بالوقت
                            </label>
                            <div class="text-center mb-3">
                                <div class="score-display bg-purple-100 text-purple-800" id="punctuality-score-display">3</div>
                            </div>
                            <div class="star-rating justify-center">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="punctuality-<?= $i ?>" name="punctuality_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                                <label for="punctuality-<?= $i ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-2 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                        </div>
                        
                        <div class="bg-orange-50 p-5 rounded-xl">
                            <label class="block text-lg font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-users ml-2 text-orange-500"></i>
                                العمل الجماعي
                            </label>
                            <div class="text-center mb-3">
                                <div class="score-display bg-orange-100 text-orange-800" id="teamwork-score-display">3</div>
                            </div>
                            <div class="star-rating justify-center">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="teamwork-<?= $i ?>" name="teamwork_score" value="<?= $i ?>" <?= $i == 3 ? 'checked' : '' ?>>
                                <label for="teamwork-<?= $i ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-2 text-center">1 (ضعيف) - 5 (ممتاز)</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-lg font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-comment-dots ml-2 text-gray-500"></i>
                        التعليقات
                    </label>
                    <textarea name="comments" rows="4" placeholder="أدخل ملاحظاتك حول أداء المدير..."
                              class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-day ml-2 text-gray-500"></i>
                            تاريخ التقييم
                        </label>
                        <div class="relative">
                            <input type="date" name="evaluation_date" value="<?= date('Y-m-d') ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-check ml-2 text-gray-500"></i>
                            موعد التقييم القادم (اختياري)
                        </label>
                        <div class="relative">
                            <input type="date" name="next_evaluation_date" 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   min="<?= date('Y-m-d', strtotime('+3 months')) ?>">
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle ml-1"></i>
                            سيتم حظر التقييمات الجديدة قبل 3 أشهر من هذا التاريخ
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 gap-3">
                    <a href="/employee-portal/public/admin/managers" 
                       class="btn-secondary px-6 py-3 text-white rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-times ml-2"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn-primary px-6 py-3 text-white rounded-lg font-medium flex items-center justify-center"
                            <?= $hasRecentEvaluation ? 'disabled' : '' ?>>
                        <i class="fas fa-save ml-2"></i>
                        حفظ التقييم
                    </button>
                </div>
                
                <?php if ($hasRecentEvaluation): ?>
                <div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg flex items-center">
                    <i class="fas fa-ban ml-2"></i>
                    <span>لا يمكن إضافة تقييم جديد بسبب وجود تقييم حديث خلال آخر 3 أشهر</span>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        // تحديث عرض النقاط عند تغيير التقييم
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('.star-rating input');
            
            ratingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const category = this.name;
                    const displayElement = document.getElementById(`${category}-display`);
                    if (displayElement) {
                        displayElement.textContent = this.value;
                        
                        // تغيير لون الخلفية بناء على القيمة
                        if (this.value >= 4) {
                            displayElement.className = 'score-display bg-green-100 text-green-800';
                        } else if (this.value >= 3) {
                            displayElement.className = 'score-display bg-yellow-100 text-yellow-800';
                        } else {
                            displayElement.className = 'score-display bg-red-100 text-red-800';
                        }
                    }
                });
            });
            
            // تهيئة الألوان الأولية
            const initialScores = {
                'performance_score': 3,
                'quality_score': 3,
                'punctuality_score': 3,
                'teamwork_score': 3
            };
            
            for (const [category, score] of Object.entries(initialScores)) {
                const displayElement = document.getElementById(`${category}-display`);
                if (displayElement) {
                    if (score >= 4) {
                        displayElement.className = 'score-display bg-green-100 text-green-800';
                    } else if (score >= 3) {
                        displayElement.className = 'score-display bg-yellow-100 text-yellow-800';
                    } else {
                        displayElement.className = 'score-display bg-red-100 text-red-800';
                    }
                }
            }
        });
    </script>
</body>
</html>