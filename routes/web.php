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
    //Route::resource('interventions', App\Http\Controllers\InterventionController::class);

    Route::patch('users/{id}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::patch('stations/{id}/toggle-status', [App\Http\Controllers\StationController::class, 'toggleStatus'])->name('stations.toggleStatus');

    // Incidents Agent
    Route::get('incidents/mes-incidents', [App\Http\Controllers\IncidentController::class, 'mesIncidents'])->name('incidents.mes_incidents');

    // Technicien / Prestataire
    Route::get('interventions/mes-interventions', [App\Http\Controllers\InterventionController::class, 'mesInterventions'])->name('interventions.mes_interventions');
    Route::post('incidents/{id}/intervenir', [App\Http\Controllers\InterventionController::class, 'intervenir'])->name('incidents.intervenir');
    Route::get('incidents/{id}/rapport', [App\Http\Controllers\InterventionController::class, 'showRapport'])->name('incidents.rapport');
    Route::post('incidents/{id}/rapport', [App\Http\Controllers\InterventionController::class, 'storeRapport'])->name('incidents.rapport.store');
    Route::post('incidents/{id}/reprendre', [App\Http\Controllers\InterventionController::class, 'reprendre'])->name('incidents.reprendre');

    // Incidents DT
    Route::get('incidents/{id}/assigner', [App\Http\Controllers\IncidentController::class, 'showAssignation'])->name('incidents.assignation');
    Route::post('incidents/{id}/assigner', [App\Http\Controllers\IncidentController::class, 'storeAssignation'])->name('incidents.assignation.store');
    Route::post('incidents/{id}/cloturer', [App\Http\Controllers\IncidentController::class, 'cloturer'])->name('incidents.cloturer');
    Route::get('incidents/{id}/historique', [App\Http\Controllers\IncidentController::class, 'historique'])->name('incidents.historique');
    Route::get('incidents/{id}/details-rapport', [App\Http\Controllers\IncidentController::class, 'detailsRapport'])->name('incidents.details_rapport');

    // Commentaires
    Route::post('incidents/{id}/commentaire', [App\Http\Controllers\CommentaireController::class, 'store'])->name('incidents.commentaire.store');

    //Réassignation
    Route::post('incidents/{id}/reassigner', [App\Http\Controllers\IncidentController::class, 'reassigner'])->name('incidents.reassigner');

    Route::resource('incidents', App\Http\Controllers\IncidentController::class);
});