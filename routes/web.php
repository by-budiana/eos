<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function() {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tasks
    Route::get('/tasks/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::resource('tasks', TaskController::class);
    
    // Users
    Route::resource('users', UserController::class);
    
    // Settings Profile
    Route::get('/settings/profile', [UserController::class, 'profile'])->name('settings.profile');
    Route::post('/settings/profile', [UserController::class, 'updateProfile'])->name('settings.profile.update');
});
