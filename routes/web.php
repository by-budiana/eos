<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AIController;

Route::get('/', function() {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Attendances
    Route::get('/attendances/dashboard', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendances.dashboard');
    Route::get('/attendances/history', [\App\Http\Controllers\AttendanceController::class, 'history'])->name('attendances.history');
    Route::post('/attendances/check-in', [\App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('/attendances/check-out', [\App\Http\Controllers\AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    Route::get('/attendances/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('attendances.export');

    // Tasks
    Route::get('/tasks/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::post('/tasks/ai-description', [AIController::class, 'generateProfessionalDescription'])->name('tasks.ai-description');
    Route::resource('tasks', TaskController::class);
    
    // Users
    Route::resource('users', UserController::class);
    
    // Settings Profile
    Route::get('/settings/profile', [UserController::class, 'profile'])->name('settings.profile');
    Route::post('/settings/profile', [UserController::class, 'updateProfile'])->name('settings.profile.update');
});
