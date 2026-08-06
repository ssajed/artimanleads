<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CallLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

// ===== مسیرهای لاگین و ثبت نام =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ===== مسیرهای تست =====
Route::get('/simple-test', [App\Http\Controllers\SimpleTestController::class, 'index']);
Route::get('/simple-view', function(){ return view('simple'); });
Route::get('/dashboard-test', function(){ return view('dashboard-test'); });

// ===== مسیرهای انتخاب نوع پروژه =====
Route::get('/projects/select-type', [ProjectController::class, 'selectType'])->name('projects.select-type');

// ===== مسیرهای احراز هویت =====
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-role', [ProfileController::class, 'updateRole'])->name('profile.update-role');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/assign', [ProjectController::class, 'assign'])->name('projects.assign');
    
    Route::patch('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    
    Route::get('/assignments/create/{project}', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::patch('/assignments/{assignment}/status', [AssignmentController::class, 'updateStatus'])->name('assignments.update-status');
    
    Route::get('/call-logs', [CallLogController::class, 'index'])->name('call-logs.index');
    Route::get('/call-logs/select-project', [CallLogController::class, 'selectProject'])->name('call-logs.select-project');
    Route::get('/call-logs/create/{project}', [CallLogController::class, 'create'])->name('call-logs.create');
    Route::post('/call-logs', [CallLogController::class, 'store'])->name('call-logs.store');
    Route::get('/call-logs/{callLog}', [CallLogController::class, 'show'])->name('call-logs.show');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markSingleRead'])->name('notifications.mark-single-read');
    
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [BackupController::class, 'delete'])->name('backup.delete');
    Route::post('/backup/restore/{filename}', [BackupController::class, 'restore'])->name('backup.restore');
    
    Route::post('equipment/upload-photo', [EquipmentController::class, 'uploadPhoto'])->name('equipment.upload-photo');
});