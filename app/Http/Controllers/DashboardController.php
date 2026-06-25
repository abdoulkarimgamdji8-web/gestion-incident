<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->nom_role;

        $isManager = in_array($role, [
            'Admin', 'Directeur Technicien', 'Responsable DT',
            'Directeur maintenance', 'Responsable maintenance',
        ]);
        $isTechnicien = in_array($role, ['Technicien', 'Prestataire Externe']);

        $baseQuery = match (true) {
            $isTechnicien => Incident::where('technicien_assigne_id', $user->id),
            $isManager    => Incident::query(),
            default       => Incident::where('declarant_id', $user->id),
        };

        $stats = [
            'total'       => (clone $baseQuery)->count(),
            'declare'     => (clone $baseQuery)->where('statut', 'declare')->count(),
            'en_cours'    => (clone $baseQuery)->whereIn('statut', ['assigne', 'en_cours', 'en_attente'])->count(),
            'resolu'      => (clone $baseQuery)->where('statut', 'resolu')->count(),
            'non_resolu'  => (clone $baseQuery)->where('statut', 'non_resolu')->count(),
            'cloture'     => (clone $baseQuery)->where('statut', 'cloture')->count(),
        ];

        $priorites = [
            'faible'   => (clone $baseQuery)->where('priorite', 'faible')->whereNotIn('statut', ['cloture'])->count(),
            'eleve'    => (clone $baseQuery)->where('priorite', 'eleve')->whereNotIn('statut', ['cloture'])->count(),
            'critique' => (clone $baseQuery)->where('priorite', 'critique')->whereNotIn('statut', ['cloture'])->count(),
        ];

        $recents = (clone $baseQuery)
            ->with(['equipement', 'technicien', 'declarant', 'station'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $equipementsEnPanne = Equipement::where('etat', 'en_panne')->count();

        $techniciensDispo = User::whereHas('role', fn($q) =>
            $q->whereIn('nom_role', ['Technicien', 'Prestataire Externe'])
        )->where('disponibilite', true)->count();

        return view('layouts.dashboard', compact(
            'stats', 'priorites', 'recents', 'equipementsEnPanne', 'techniciensDispo', 'isManager'
        ));
    }
}
