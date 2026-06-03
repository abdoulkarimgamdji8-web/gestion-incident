<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\DashboardController;

// Public auth routes
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Protected auth routes (requires token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('auth.logout_all');
    Route::post('/check-role', [AuthController::class, 'checkRole'])->name('auth.check_role');

    // Dashboard routes - all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Admin-only routes
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminPanel'])->name('dashboard.admin');
    });

    // Technician routes
    Route::middleware('role:Technicien,Responsable DT,Directeur Technicien')->group(function () {
        Route::get('/technician/dashboard', [DashboardController::class, 'technicianPanel'])->name('dashboard.technician');
    });

    // User management - Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::apiResource('users', \App\Http\Controllers\UserController::class);
    });
});

