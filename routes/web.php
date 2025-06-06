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
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;




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
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Settings
    Route::get('/profile-settings', [ProfileSettingsController::class, 'index'])->name('profile.settings');

    // Add this inside the auth middleware group in web.php
    Route::post('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update');

    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Sidebar Routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');

    Route::get('/leave', [LeaveController::class, 'index'])->name('leave');

    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll');

    Route::get('/travel', [TravelController::class, 'index'])->name('travel');
    
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});

Route::get('/dashboard/department-data', [DashboardController::class, 'getDepartmentData']);




