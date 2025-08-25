<?php
namespace App\Controllers;

use App\Models\Leave;
use App\Core\Auth;
use App\Core\App;

class AdminLeaveController extends BaseController
{
    public function index()
    {
        Auth::check();
        $leaves = Leave::all();
        $this->render('admin/leaves/index.php', [
            'leaves' => $leaves,
            'hasPending' => Leave::hasPendingLeaves()
        ]);
    }
    
    public function pending()
    {
        Auth::check();
        $leaves = Leave::pending();
        $this->render('admin/leaves/pending.php', [
            'leaves' => $leaves,
            'hasPending' => true
        ]);
    }
    
    public function show($id)
    {
        Auth::check();
        $leave = Leave::find($id);
        
        if (!$leave) {
            $_SESSION['error'] = "طلب الإجازة غير موجود";
            header("Location: /employee-portal/public/admin/leaves");
            exit;
        }
        
        $this->render('admin/leaves/show.php', [
            'leave' => $leave
        ]);
    }
    
 public function approve($id)
{
    Auth::check();
    
    try {
        $result = Leave::approve($id, $_SESSION['user']['id'], $_POST['comments'] ?? null);
        
        if ($result) {
            $_SESSION['success'] = "تم الموافقة على طلب الإجازة بنجاح وتم تحديث رصيد الموظف";
        } else {
            $_SESSION['error'] = "فشل في الموافقة على طلب الإجازة";
        }
    } catch (\Throwable $e) {
        $_SESSION['error'] = "خطأ: " . $e->getMessage();
    }
    
    header("Location: /employee-portal/public/admin/leaves");
    exit;
}

    
    public function reject($id)
    {
        Auth::check();
        
        try {
            $result = Leave::reject($id, $_SESSION['user']['id'], $_POST['comments'] ?? null);
            
            if ($result) {
                $_SESSION['success'] = "تم رفض طلب الإجازة بنجاح";
            } else {
                $_SESSION['error'] = "فشل في رفض طلب الإجازة";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/leaves");
        exit;
    }
    
    public function delete($id)
    {
        Auth::check();
        
        try {
            $result = Leave::softDelete($id);
            
            if ($result) {
                $_SESSION['success'] = "تم حذف طلب الإجازة بنجاح";
            } else {
                $_SESSION['error'] = "فشل في حذف طلب الإجازة";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/leaves");
        exit;
    }
    
    public function trash()
    {
        Auth::check();
        $leaves = Leave::allTrash();
        $this->render('admin/leaves/trash.php', [
            'leaves' => $leaves
        ]);
    }
    
    public function restore($id)
    {
        Auth::check();
        
        try {
            $result = Leave::restore($id);
            
            if ($result) {
                $_SESSION['success'] = "تم استعادة طلب الإجازة بنجاح";
            } else {
                $_SESSION['error'] = "فشل في استعادة طلب الإجازة";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/leaves/trash");
        exit;
    }
    
    public function deleteFinal($id)
    {
        Auth::check();
        $db = App::db();
        
        try {
            $stmt = $db->prepare("DELETE FROM leaves WHERE id = :id");
            $result = $stmt->execute(['id' => $id]);
            
            if ($result) {
                $_SESSION['success'] = "تم الحذف النهائي لطلب الإجازة بنجاح";
            } else {
                $_SESSION['error'] = "فشل في الحذف النهائي";
            }
        } catch (\Throwable $e) {
            $_SESSION['error'] = "خطأ: " . $e->getMessage();
        }
        
        header("Location: /employee-portal/public/admin/leaves/trash");
        exit;
    }
}