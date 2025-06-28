<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\EmployeeLeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('login.show');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.show');
    
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registration Routes
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
    
    // Settings
    Route::get('/profile-settings', [ProfileSettingsController::class, 'index'])->name('profile.settings');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::get('/admin-employees', [EmployeeController::class, 'index'])->name('admin.employees');
    Route::prefix('employees')->group(function() {
        Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // Attendance
    Route::get('/admin-attendance', [AttendanceController::class, 'index'])->name('admin.attendance');
    Route::prefix('attendance')->group(function() {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance');
        Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
        Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
        Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::get('/export', [AttendanceController::class, 'export'])->name('attendance.export');
        Route::get('/monthly-comparison-data', [AttendanceController::class, 'getMonthlyComparisonData'])->name('attendance.monthly-comparison-data');
    });

    // Leave
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave');
    Route::get('/admin-leave', [LeaveController::class, 'index'])->name('admin.leave');
    Route::prefix('leave')->group(function() {
        Route::put('/leave/{leave}', [LeaveController::class, 'update'])->name('leave.update');
        Route::post('/', [LeaveController::class, 'store'])->name('leave.store');
        Route::put('/{leave}', [LeaveController::class, 'update'])->name('leave.update');
    });

    // Travel
    Route::get('/travel', [TravelController::class, 'index'])->name('travel');
    Route::get('/admin-travel', [TravelController::class, 'index'])->name('admin.travel');

    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // Notification Routes
    Route::prefix('notifications')->group(function() {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    });
});

Route::get('/dashboard/department-data', [DashboardController::class, 'getDepartmentData']);