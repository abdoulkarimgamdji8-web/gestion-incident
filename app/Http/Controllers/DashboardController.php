<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\User;
use App\Models\Station;
use App\Models\Equipement;
use App\Models\Domaine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->nom_role;

        // Valeurs par défaut
        $stats = [];
        $priorites = [];
        $recents = collect();
        $equipementsEnPanne = 0;
        $techniciensDispo = 0;
        $topStations = collect();
        $pannesRecurrentes = collect();
        $moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $moisData = array_fill(0, 12, 0);
        $data = [];

        // ==================== ADMINISTRATEUR SYSTÈME ====================
        if ($role === 'Administrateur système') {
            $data = [
                'total_users'       => User::count(),
                'total_stations'    => Station::where('statut', 1)->count(),
                'total_equipements' => Equipement::count(),
                'total_domaines'    => Domaine::count(),
            ];
        }

        // ==================== DIRECTEUR / RESPONSABLE MAINTENANCE ====================
        elseif (in_array($role, ['Directeur maintenance', 'Responsable maintenance'])) {

            $stats = [
                'total'       => Incident::count(),
                'declare'     => Incident::where('statut', 'declare')->count(),
                'assigne'     => Incident::where('statut', 'assigne')->count(),
                'en_cours'    => Incident::whereIn('statut', ['assigne', 'en_cours', 'en_attente'])->count(),
                'en_attente'  => Incident::where('statut', 'en_attente')->count(),
                'resolu'      => Incident::where('statut', 'resolu')->count(),
                'non_resolu'  => Incident::where('statut', 'non_resolu')->count(),
                'cloture'     => Incident::where('statut', 'cloture')->count(),
            ];

            $priorites = [
                'critique' => Incident::whereNotIn('statut', ['cloture'])->where('priorite', 'critique')->count(),
                'eleve'    => Incident::whereNotIn('statut', ['cloture'])->where('priorite', 'eleve')->count(),
                'faible'   => Incident::whereNotIn('statut', ['cloture'])->where('priorite', 'faible')->count(),
            ];

            $equipementsEnPanne = Equipement::where('etat', 'en_panne')->count();
            $techniciensDispo   = User::whereHas(
                'role',
                fn($q) =>
                $q->where('nom_role', 'Technicien')
                    ->orWhere('nom_role', 'Prestataire Externe')
            )->where('disponibilite', true)->count();

            $recents = Incident::with(['station', 'technicien'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // Top 5 stations
            $topStations = DB::table('incident')
                ->join('station', 'incident.station_id', '=', 'station.id')
                ->select('station.nom', DB::raw('COUNT(incident.id) as total'))
                ->groupBy('station.id', 'station.nom')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // Top 5 pannes récurrentes
            $pannesRecurrentes = DB::table('incident')
                ->select('titre', DB::raw('COUNT(*) as total'))
                ->groupBy('titre')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // Incidents par mois
            $incidentsParMois = DB::table('incident')
                ->select(
                    DB::raw('MONTH(date_signalement) as mois'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('date_signalement', date('Y'))
                ->groupBy(DB::raw('MONTH(date_signalement)'))
                ->orderBy('mois')
                ->get()
                ->keyBy('mois');

            for ($i = 1; $i <= 12; $i++) {
                $moisData[$i - 1] = $incidentsParMois->has($i) ? $incidentsParMois[$i]->total : 0;
            }
        }

        // ==================== SOUS-GÉRANT DE STATION ====================
        elseif ($role === 'Sous-gérant de station') {
            $stats = [
                'total'      => Incident::where('declarant_id', $user->id)->count(),
                'declare'    => Incident::where('declarant_id', $user->id)->where('statut', 'declare')->count(),
                'en_cours'   => Incident::where('declarant_id', $user->id)->whereIn('statut', ['assigne', 'en_cours', 'en_attente'])->count(),
                'resolu'     => Incident::where('declarant_id', $user->id)->where('statut', 'resolu')->count(),
                'non_resolu' => Incident::where('declarant_id', $user->id)->where('statut', 'non_resolu')->count(),
                'cloture'    => Incident::where('declarant_id', $user->id)->where('statut', 'cloture')->count(),
            ];

            $priorites = [
                'critique' => Incident::where('declarant_id', $user->id)->whereNotIn('statut', ['cloture'])->where('priorite', 'critique')->count(),
                'eleve'    => Incident::where('declarant_id', $user->id)->whereNotIn('statut', ['cloture'])->where('priorite', 'eleve')->count(),
                'faible'   => Incident::where('declarant_id', $user->id)->whereNotIn('statut', ['cloture'])->where('priorite', 'faible')->count(),
            ];

            $equipementsEnPanne = 0;
            $techniciensDispo   = 0;

            $recents = Incident::with(['station', 'technicien'])
                ->where('declarant_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        // ==================== TECHNICIEN / PRESTATAIRE EXTERNE ====================
        elseif (in_array($role, ['Technicien', 'Prestataire Externe'])) {
            $stats = [
                'total'      => Incident::where('technicien_assigne_id', $user->id)->count(),
                'declare'    => 0,
                'en_cours'   => Incident::where('technicien_assigne_id', $user->id)->whereIn('statut', ['assigne', 'en_cours', 'en_attente'])->count(),
                'resolu'     => Incident::where('technicien_assigne_id', $user->id)->where('statut', 'resolu')->count(),
                'non_resolu' => Incident::where('technicien_assigne_id', $user->id)->where('statut', 'non_resolu')->count(),
                'cloture'    => Incident::where('technicien_assigne_id', $user->id)->where('statut', 'cloture')->count(),
            ];

            $priorites = [
                'critique' => Incident::where('technicien_assigne_id', $user->id)->whereNotIn('statut', ['cloture'])->where('priorite', 'critique')->count(),
                'eleve'    => Incident::where('technicien_assigne_id', $user->id)->whereNotIn('statut', ['cloture'])->where('priorite', 'eleve')->count(),
                'faible'   => Incident::where('technicien_assigne_id', $user->id)->whereNotIn('statut', ['cloture'])->where('priorite', 'faible')->count(),
            ];

            $equipementsEnPanne = 0;
            $techniciensDispo   = 0;

            $recents = Incident::with(['station', 'technicien'])
                ->where('technicien_assigne_id', $user->id)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
        }

        return view('layouts.dashboard', compact(
            'role',
            'data',
            'stats',
            'priorites',
            'recents',
            'equipementsEnPanne',
            'techniciensDispo',
            'topStations',
            'pannesRecurrentes',
            'moisLabels',
            'moisData'
        ));
    }
}