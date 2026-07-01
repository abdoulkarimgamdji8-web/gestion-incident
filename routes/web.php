<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // ===== DASHBOARD (tous les rôles) =====
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    // ===== ADMINISTRATEUR SYSTÈME =====
    Route::middleware(['role:Administrateur système'])->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('domaines', App\Http\Controllers\DomaineController::class);
        Route::resource('stations', App\Http\Controllers\StationController::class);
        Route::resource('equipements', App\Http\Controllers\EquipementController::class);
        Route::patch('users/{id}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::patch('stations/{id}/toggle-status', [App\Http\Controllers\StationController::class, 'toggleStatus'])->name('stations.toggleStatus');
        Route::get('historiques', [App\Http\Controllers\HistoriqueController::class, 'index'])->name('historique.index');
        
    });

    // ===== DIRECTION MAINTENANCE =====
    Route::middleware(['role:Directeur maintenance,Responsable maintenance'])->group(function () {
        Route::get('incidents', [App\Http\Controllers\IncidentController::class, 'index'])->name('incidents.index');
        Route::get('incidents/{id}/assigner', [App\Http\Controllers\IncidentController::class, 'showAssignation'])->name('incidents.assignation');
        Route::post('incidents/{id}/assigner', [App\Http\Controllers\IncidentController::class, 'storeAssignation'])->name('incidents.assignation.store');
        Route::post('incidents/{id}/cloturer', [App\Http\Controllers\IncidentController::class, 'cloturer'])->name('incidents.cloturer');
        Route::post('incidents/{id}/reassigner', [App\Http\Controllers\IncidentController::class, 'reassigner'])->name('incidents.reassigner');
        Route::get('incidents/{id}/details-rapport', [App\Http\Controllers\IncidentController::class, 'detailsRapport'])->name('incidents.details_rapport');
        Route::get('incidents/{id}/historiques', [App\Http\Controllers\IncidentController::class, 'historiques'])->name('incidents.historiques');
    });

    // ===== SOUS-GÉRANT DE STATION =====
    Route::middleware(['role:Sous-gérant de station'])->group(function () {
        Route::get('incidents/mes-incidents', [App\Http\Controllers\IncidentController::class, 'mesIncidents'])->name('incidents.mes_incidents');
        Route::get('incidents/create', [App\Http\Controllers\IncidentController::class, 'create'])->name('incidents.create');
        Route::post('incidents', [App\Http\Controllers\IncidentController::class, 'store'])->name('incidents.store');
        Route::get('incidents/{id}/edit', [App\Http\Controllers\IncidentController::class, 'edit'])->name('incidents.edit');
        Route::put('incidents/{id}', [App\Http\Controllers\IncidentController::class, 'update'])->name('incidents.update');
        Route::delete('incidents/{id}', [App\Http\Controllers\IncidentController::class, 'destroy'])->name('incidents.destroy');
    });

    // ===== TECHNICIEN / PRESTATAIRE EXTERNE =====
    Route::middleware(['role:Technicien,Prestataire Externe'])->group(function () {
        Route::get('interventions/mes-interventions', [App\Http\Controllers\InterventionController::class, 'mesInterventions'])->name('interventions.mes_interventions');
        Route::post('incidents/{id}/intervenir', [App\Http\Controllers\InterventionController::class, 'intervenir'])->name('incidents.intervenir');
        Route::get('incidents/{id}/rapport', [App\Http\Controllers\InterventionController::class, 'showRapport'])->name('incidents.rapport');
        Route::post('incidents/{id}/rapport', [App\Http\Controllers\InterventionController::class, 'storeRapport'])->name('incidents.rapport.store');
        Route::post('incidents/{id}/reprendre', [App\Http\Controllers\InterventionController::class, 'reprendre'])->name('incidents.reprendre');
    });

    // ===== ROUTES COMMUNES (tous les rôles connectés) =====
    Route::get('incidents/{id}', [App\Http\Controllers\IncidentController::class, 'show'])->name('incidents.show');
    Route::post('incidents/{id}/commentaire', [App\Http\Controllers\CommentaireController::class, 'store'])->name('incidents.commentaire.store');
});