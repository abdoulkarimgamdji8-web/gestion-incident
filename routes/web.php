<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::resource('dashboard', App\Http\Controllers\DashboardController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('domaines', App\Http\Controllers\DomaineController::class);
    Route::resource('stations', App\Http\Controllers\StationController::class);
    Route::resource('equipements', App\Http\Controllers\EquipementController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('interventions', App\Http\Controllers\InterventionController::class);

    Route::patch('users/{id}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::patch('stations/{id}/toggle-status', [App\Http\Controllers\StationController::class, 'toggleStatus'])->name('stations.toggleStatus');

    // Incidents Agent
    Route::get('incidents/mes-incidents', [App\Http\Controllers\IncidentController::class, 'mesIncidents'])->name('incidents.mes_incidents');

    // Incidents DT
    Route::get('incidents/{id}/assigner', [App\Http\Controllers\IncidentController::class, 'showAssignation'])->name('incidents.assignation');
    Route::post('incidents/{id}/assigner', [App\Http\Controllers\IncidentController::class, 'storeAssignation'])->name('incidents.assignation.store');
    Route::post('incidents/{id}/cloturer', [App\Http\Controllers\IncidentController::class, 'cloturer'])->name('incidents.cloturer');
    Route::get('incidents/{id}/historique', [App\Http\Controllers\IncidentController::class, 'historique'])->name('incidents.historique');

    Route::resource('incidents', App\Http\Controllers\IncidentController::class);
});