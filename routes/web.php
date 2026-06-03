<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/dashboard', function () {
    return view('layouts.dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('users', App\Http\Controllers\UserController::class);
Route::resource('dashboard', App\Http\Controllers\DashboardController::class);
Route::resource('roles', App\Http\Controllers\RoleController::class);
Route::resource('domaines', App\Http\Controllers\DomaineController::class);
Route::resource('stations', App\Http\Controllers\StationController::class);
Route::resource('equipements', App\Http\Controllers\EquipementController::class);

Route::middleware(['auth'])->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('dashboard', App\Http\Controllers\DashboardController::class);
});