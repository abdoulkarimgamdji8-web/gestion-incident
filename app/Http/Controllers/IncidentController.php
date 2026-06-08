<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Equipement;
use App\Models\Historique;
use App\Models\Incident;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    // ==================== AGENT ====================

    // Liste des incidents de l'agent connecté
    public function mesIncidents()
    {
        $incidents = Incident::with(['domaine', 'station', 'equipement'])
            ->where('declarant_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('layouts.incidentsAgent.mes_incidents', compact('incidents'));
    }

    // Formulaire de déclaration
    public function create()
    {
        $domaines = Domaine::orderBy('nom_domaine')->get();
        $stations = Station::where('statut', 1)->orderBy('nom')->get();

        $equipementsOccupes = Incident::whereIn('statut', ['declare', 'assigne', 'en_cours'])
            ->pluck('equipement_id')
            ->toArray();

        $equipements = Equipement::whereNotIn('id', $equipementsOccupes)
            ->whereHas('station', fn($q) => $q->where('statut', 1))
            ->orderBy('nom')
            ->get();

        return view('layouts.incidentsAgent.declarer', compact('domaines', 'stations', 'equipements'));
    }

    // Enregistrement de la déclaration
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'priorite'    => 'required|in:faible,eleve,critique',
            'domaine_id'  => 'required|exists:domaines,id',
            'station_id'  => 'required|exists:station,id',
            'equipement_id' => [
                'required',
                'exists:equipement,id',
                function ($attribute, $value, $fail) {
                    $incidentActif = Incident::whereIn('statut', ['declare', 'assigne', 'en_cours'])
                        ->where('equipement_id', $value)
                        ->exists();

                    if ($incidentActif) {
                        $fail('Cet équipement a déjà un incident en cours.');
                    }
                }
            ],
        ]);

        $validated['statut']           = 'declare';
        $validated['date_signalement'] = now();
        $validated['declarant_id']     = Auth::id();

        Incident::create($validated);

        return redirect()->route('incidents.mes_incidents')
            ->with('success', 'Incident déclaré avec succès.');
    }

    // Détails d'un incident (agent)
    public function show(string $id)
    {
        $incident = Incident::with(['domaine', 'station', 'equipement', 'declarant', 'technicien'])
            ->findOrFail($id);

        return view('layouts.incidentsAgent.details', compact('incident'));
    }

    // Formulaire de modification (agent, avant assignation)
    public function edit(string $id)
    {
        $incident = Incident::findOrFail($id);

        if ($incident->statut !== 'declare') {
            return redirect()->route('incidents.mes_incidents')
                ->with('error', 'Cet incident ne peut plus être modifié.');
        }

        $domaines = Domaine::orderBy('nom_domaine')->get();
        $stations = Station::where('statut', 1)->orderBy('nom')->get();

        $equipementsOccupes = Incident::whereIn('statut', ['declare', 'assigne', 'en_cours'])
            ->where('id', '!=', $incident->id)
            ->pluck('equipement_id')
            ->toArray();

        $equipements = Equipement::whereNotIn('id', $equipementsOccupes)
            ->orderBy('nom')
            ->get();

        return view('layouts.incidentsAgent.modifier', compact('incident', 'domaines', 'stations', 'equipements'));
    }

    // Mise à jour (agent, avant assignation)
    public function update(Request $request, string $id)
    {
        $incident = Incident::findOrFail($id);

        if ($incident->statut !== 'declare') {
            return redirect()->route('incidents.mes_incidents')
                ->with('error', 'Cet incident ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'priorite'     => 'required|in:faible,eleve,critique',
            'domaine_id'   => 'required|exists:domaines,id',
            'station_id'   => 'required|exists:station,id',
            'equipement_id' => 'required|exists:equipement,id',
        ]);

        $incident->update($validated);

        return redirect()->route('incidents.mes_incidents')
            ->with('success', 'Incident modifié avec succès.');
    }

    // ==================== DIRECTION TECHNIQUE ====================

    // Liste de tous les incidents
    public function index()
    {
        $incidents = Incident::with(['domaine', 'station', 'equipement', 'declarant', 'technicien'])
            ->orderByDesc('created_at')
            ->get();

        return view('incidentsDT.tous_les_incidents', compact('incidents'));
    }

    // Formulaire d'assignation
    public function showAssignation(string $id)
    {
        $incident = Incident::with(['domaine', 'station', 'equipement', 'declarant'])
            ->findOrFail($id);

        if ($incident->statut !== 'declare') {
            return redirect()->route('incidents.index')
                ->with('error', 'Cet incident est déjà assigné.');
        }

        $intervenants = User::whereHas('role', fn($q) => $q->where('nom_role', 'Technicien')->orWhere('nom_role', 'Prestataire Externe'))
            ->where('domaine_id', $incident->domaine_id)
            ->where('disponibilite', true)
            ->orderBy('nom')
            ->get();

        return view('incidentsDT.assignation', compact('incident', 'intervenants'));
    }

    // Traitement de l'assignation
    public function storeAssignation(Request $request, string $id)
    {
        $incident = Incident::findOrFail($id);

        $validated = $request->validate([
            'technicien_assigne_id' => 'required|exists:users,id',
        ]);

        // Marquer le technicien comme occupé
        $technicien = User::findOrFail($validated['technicien_assigne_id']);
        $technicien->update(['disponibilite' => false]);

        // Mettre à jour l'incident
        $incident->update([
            'technicien_assigne_id' => $validated['technicien_assigne_id'],
            'statut'                => 'assigne',
        ]);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident assigné avec succès.');
    }

    // Clôturer un incident résolu
    public function cloturer(string $id)
    {
        $incident = Incident::findOrFail($id);

        if ($incident->statut !== 'resolu') {
            return redirect()->route('incidents.index')
                ->with('error', 'Seul un incident résolu peut être clôturé.');
        }

        // Libérer le technicien
        if ($incident->technicien_assigne_id) {
            User::where('id', $incident->technicien_assigne_id)
                ->update(['disponibilite' => true]);
        }

        $incident->update(['statut' => 'cloture']);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident clôturé avec succès.');
    }

    // Historique
    public function historique(string $id)
    {
        $incident    = Incident::with(['domaine', 'station', 'equipement', 'declarant'])->findOrFail($id);
        $historiques = Historique::where('user_id', Auth::id())->orderByDesc('date_action')->get();

        return view('incidentsDT.historique', compact('incident', 'historiques'));
    }

    // Suppression
    public function destroy(string $id)
    {
        Incident::findOrFail($id)->delete();

        return redirect()->route('incidents.index')
            ->with('success', 'Incident supprimé.');
    }
}