<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة المهملات - إدارة الموظفين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
        }

        [data-theme="dark"] {
            --primary-color: #60a5fa;
            --secondary-color: #3b82f6;
            --text-color: #e5e7eb;
            --bg-color: #111827;
            --card-bg: #1f2937;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .trash-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .trash-table th {
            background-color: #f3f4f6;
            padding: 0.75rem;
            text-align: right;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .trash-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .trash-table tr:last-child td {
            border-bottom: none;
        }

        .trash-table tr:hover {
            background-color: #f9fafb;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            margin-left: 0.5rem;
        }

        .btn-restore {
            background-color: #10b981;
            color: white;
        }

        .btn-restore:hover {
            background-color: #059669;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        .btn-back {
            background-color: #6b7280;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            margin-top: 1rem;
        }

        .btn-back:hover {
            background-color: #4b5563;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .empty-state-icon {
            font-size: 3rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            
            .trash-table {
                min-width: 600px;
            }
            
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn {
                margin-left: 0;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-red-500">delete</span>
                    سلة المهملات
                </h1>
                <p class="text-gray-600 mt-2">إدارة الموظفين المحذوفين</p>
            </div>
            <a href="/employee-portal/public/admin/employees" class="btn-back mt-4 md:mt-0">
                <span class="material-icons ml-2">arrow_back</span>
                العودة للقائمة
            </a>
        </div>

        <?php if (!empty($employees)): ?>
        <div class="table-container">
            <table class="trash-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>القسم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($employees as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['name']) ?></td>
                        <td><?= htmlspecialchars($emp['email']) ?></td>
                        <td><?= htmlspecialchars($emp['department']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <form method="post" action="/employee-portal/public/admin/employees/restore/<?= $emp['id'] ?>" style="display:inline">
                                    <button type="submit" class="btn btn-restore">
                                        <span class="material-icons mr-1" style="font-size: 18px;">restore</span>
                                        استعادة
                                    </button>
                                </form>
                                <form method="post" action="/employee-portal/public/admin/employees/delete-final/<?= $emp['id'] ?>" style="display:inline" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا الموظف نهائياً؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                    <button type="submit" class="btn btn-delete">
                                        <span class="material-icons mr-1" style="font-size: 18px;">delete_forever</span>
                                        حذف نهائي
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <span class="material-icons">delete_sweep</span>
            </div>
            <h3 class="text-xl font-medium text-gray-700">سلة المهملات فارغة</h3>
            <p class="text-gray-500 mt-2">لا توجد موظفين محذوفين حالياً</p>
            <a href="/employee-portal/public/admin/employees" class="btn-back inline-flex mt-4">
                <span class="material-icons ml-2">arrow_back</span>
                العودة للقائمة الرئيسية
            </a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // تأكيد الحذف النهائي
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[onsubmit]');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (confirm('هل أنت متأكد أنك تريد حذف هذا الموظف نهائياً؟ هذا الإجراء لا يمكن التراجع عنه.')) {
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>