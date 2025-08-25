<div class="container-fluid">
    <h1 class="text-2xl font-bold mb-6">الملف الشخصي</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- المعلومات الأساسية -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">المعلومات الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($employee['name']) ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($employee['email']) ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">القسم</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($employee['department']) ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الوظيفة</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($employee['position']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($employee['role']) ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- التقييمات -->
            <?php if (!empty($evaluations)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">التقييمات</h5>
                </div>
                <div class="card-body">
                    <!-- المعدل العام -->
                    <div class="text-center mb-4 p-3 bg-light rounded">
                        <h4>المعدل العام</h4>
                        <div class="display-4 fw-bold text-primary">
                            <?= number_format($averageScores['overall_avg'] ?? 0, 1) ?>/5
                        </div>
                        <div class="rating-stars mb-2">
                            <?php
                            $overallAvg = $averageScores['overall_avg'] ?? 0;
                            for ($i = 1; $i <= 5; $i++): 
                                $starClass = $i <= $overallAvg ? 'text-warning' : 'text-muted';
                            ?>
                                <i class="fas fa-star <?= $starClass ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <small class="text-muted">بناءً على <?= $averageScores['total_evaluations'] ?? 0 ?> تقييم</small>
                    </div>

                    <!-- المعدلات التفصيلية -->
                    <div class="row text-center mb-4">
                        <div class="col-6 mb-3">
                            <div class="p-2 border rounded">
                                <small class="d-block text-muted">أداء العمل</small>
                                <strong class="text-primary"><?= number_format($averageScores['avg_performance'] ?? 0, 1) ?></strong>
                                <div class="small">
                                    <?php
                                    $performance = $averageScores['avg_performance'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++): 
                                        $starClass = $i <= $performance ? 'text-warning' : 'text-muted';
                                    ?>
                                        <i class="fas fa-star <?= $starClass ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-2 border rounded">
                                <small class="d-block text-muted">جودة العمل</small>
                                <strong class="text-primary"><?= number_format($averageScores['avg_quality'] ?? 0, 1) ?></strong>
                                <div class="small">
                                    <?php
                                    $quality = $averageScores['avg_quality'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++): 
                                        $starClass = $i <= $quality ? 'text-warning' : 'text-muted';
                                    ?>
                                        <i class="fas fa-star <?= $starClass ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-2 border rounded">
                                <small class="d-block text-muted">الالتزام بالمواعيد</small>
                                <strong class="text-primary"><?= number_format($averageScores['avg_punctuality'] ?? 0, 1) ?></strong>
                                <div class="small">
                                    <?php
                                    $punctuality = $averageScores['avg_punctuality'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++): 
                                        $starClass = $i <= $punctuality ? 'text-warning' : 'text-muted';
                                    ?>
                                        <i class="fas fa-star <?= $starClass ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-2 border rounded">
                                <small class="d-block text-muted">العمل الجماعي</small>
                                <strong class="text-primary"><?= number_format($averageScores['avg_teamwork'] ?? 0, 1) ?></strong>
                                <div class="small">
                                    <?php
                                    $teamwork = $averageScores['avg_teamwork'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++): 
                                        $starClass = $i <= $teamwork ? 'text-warning' : 'text-muted';
                                    ?>
                                        <i class="fas fa-star <?= $starClass ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- المعلومات الإضافية والتقييمات التفصيلية -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">المعلومات الإضافية</h5>
                </div>
                <div class="card-body">
                    <form action="/employee-portal/public/manager/profile/update" method="POST">
                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($employee['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">نوع العمل</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($employee['work_type']) ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">رصيد الإجازات</label>
                            <input type="text" class="form-control" value="<?= $employee['leaveBalance'] ?> يوم" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">تاريخ التعيين</label>
                            <input type="text" class="form-control" value="<?= $employee['hire_date'] ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">الراتب الأساسي</label>
                            <input type="text" class="form-control" value="<?= number_format($employee['salary'], 2) ?> ر.ي" readonly>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>

            <!-- التقييمات التفصيلية -->
            <?php if (!empty($evaluations)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">التقييمات التفصيلية</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="evaluationsAccordion">
                        <?php foreach ($evaluations as $index => $eval): ?>
                        <?php
                        $avgScore = ($eval['performance_score'] + $eval['quality_score'] + 
                                   $eval['punctuality_score'] + $eval['teamwork_score']) / 4;
                        $ratingColor = $avgScore >= 4 ? 'text-success' : 
                                      ($avgScore >= 3 ? 'text-warning' : 'text-danger');
                        ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $index ?>">
                                <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" 
                                        aria-expanded="false" aria-controls="collapse<?= $index ?>">
                                    <span class="me-2 <?= $ratingColor ?>">
                                        <i class="fas fa-chart-line"></i>
                                        <?= number_format($avgScore, 1) ?>/5
                                    </span>
                                    <small class="text-muted ms-2"><?= $eval['evaluation_date'] ?></small>
                                </button>
                            </h2>
                            <div id="collapse<?= $index ?>" class="accordion-collapse collapse" 
                                 aria-labelledby="heading<?= $index ?>" data-bs-parent="#evaluationsAccordion">
                                <div class="accordion-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">المقيّم:</small>
                                            <div><?= htmlspecialchars($eval['evaluator_name']) ?></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">التاريخ:</small>
                                            <div><?= $eval['evaluation_date'] ?></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">أداء العمل:</small>
                                            <div>
                                                <?= $eval['performance_score'] ?>/5
                                                <div class="small">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $eval['performance_score'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">جودة العمل:</small>
                                            <div>
                                                <?= $eval['quality_score'] ?>/5
                                                <div class="small">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $eval['quality_score'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">الالتزام بالمواعيد:</small>
                                            <div>
                                                <?= $eval['punctuality_score'] ?>/5
                                                <div class="small">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $eval['punctuality_score'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">العمل الجماعي:</small>
                                            <div>
                                                <?= $eval['teamwork_score'] ?>/5
                                                <div class="small">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $eval['teamwork_score'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($eval['comments'])): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">ملاحظات:</small>
                                        <div class="p-2 bg-light rounded">
                                            <?= nl2br(htmlspecialchars($eval['comments'])) ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($eval['next_evaluation_date'])): ?>
                                    <div>
                                        <small class="text-muted">موعد التقييم القادم:</small>
                                        <div class="text-primary"><?= $eval['next_evaluation_date'] ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد تقييمات حتى الآن</h5>
                    <p class="text-muted">سيظهر هنا تقييمات أدائك عند توفرها</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.rating-stars {
    font-size: 1.5rem;
}
.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #495057;
}
</style>