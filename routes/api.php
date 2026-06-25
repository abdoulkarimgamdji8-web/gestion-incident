<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\InterventionController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\PieceJointeController;

// Authentification publique
Route::post('/login', [AuthController::class, 'apiLogin'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me',           [AuthController::class, 'me'])->name('auth.me');
    Route::post('/logout',      [AuthController::class, 'apiLogout'])->name('auth.logout');
    Route::post('/logout-all',  [AuthController::class, 'logoutAll'])->name('auth.logout_all');
    Route::post('/check-role',  [AuthController::class, 'checkRole'])->name('auth.check_role');

    // Dashboard
    Route::get('/dashboard',           [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/admin/dashboard',     [DashboardController::class, 'adminPanel'])->name('dashboard.admin');
    Route::get('/technician/dashboard',[DashboardController::class, 'technicianPanel'])->name('dashboard.technician');

    // Incidents
    Route::get('/incidents',                         [IncidentController::class, 'index'])->name('api.incidents.index');
    Route::post('/incidents',                        [IncidentController::class, 'store'])->name('api.incidents.store');
    Route::get('/incidents/{id}',                    [IncidentController::class, 'show'])->name('api.incidents.show');
    Route::post('/incidents/{id}/assign',            [IncidentController::class, 'assign'])->name('api.incidents.assign');
    Route::post('/incidents/{id}/cloturer',          [IncidentController::class, 'cloturer'])->name('api.incidents.cloturer');

    // Interventions
    Route::post('/incidents/{id}/intervenir',        [InterventionController::class, 'intervenir'])->name('api.interventions.intervenir');
    Route::post('/incidents/{id}/rapport',           [InterventionController::class, 'storeRapport'])->name('api.interventions.rapport');

    // Commentaires
    Route::get('/incidents/{incidentId}/commentaires',  [CommentaireController::class, 'index'])->name('api.commentaires.index');
    Route::post('/incidents/{incidentId}/commentaires', [CommentaireController::class, 'store'])->name('api.commentaires.store');
    Route::delete('/commentaires/{id}',                 [CommentaireController::class, 'destroy'])->name('api.commentaires.destroy');

    // Pièces jointes
    Route::post('/incidents/{incidentId}/fichiers', [PieceJointeController::class, 'store'])->name('api.fichiers.store');
    Route::delete('/fichiers/{id}',                  [PieceJointeController::class, 'destroy'])->name('api.fichiers.destroy');

    // Gestion utilisateurs (Admin uniquement)
    Route::middleware('role:Admin')->group(function () {
        Route::apiResource('users', \App\Http\Controllers\UserController::class);
    });
});
