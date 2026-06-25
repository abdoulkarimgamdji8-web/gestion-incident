<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Equipement;
use App\Models\Incident;
use App\Models\Station;
use App\Models\User;
use App\Models\Pieces;
use App\Models\Intervention;
use App\Models\historique;
use App\Models\notification as NotifModel;
use App\Notifications\IncidentDeclareNotification;
use App\Notifications\IncidentAssigneNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    // ==================== AGENT ====================

    // Liste des incidents de l'agent connecté
    public function mesIncidents(Request $request)
    {
        $query = Incident::with(['domaine', 'station', 'equipement'])
            ->where('declarant_id', Auth::id())
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('titre', 'like', "%{$s}%")
                                      ->orWhere('description', 'like', "%{$s}%"));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $incidents = $query->get();

        return view('layouts.incidentsAgent.mes_incidents', compact('incidents'));
    }

    // Cloturer un incident résolu
    public function cloturer(string $id)
    {
        $incident = Incident::with(['commentaires.user.role', 'interventions'])
            ->findOrFail($id);

        if ($incident->statut !== 'resolu') {
            return back()->with(
                'error',
                'Seul un incident résolu peut être clôturé.'
            );
        }

        // Vérifier que l'utilisateur connecté est Directeur ou Responsable maintenance
        $role = Auth::user()->role->nom_role;

        if (!in_array($role, ['Directeur maintenance', 'Responsable maintenance'])) {

            return back()->with(
                'error',
                'Vous n\'êtes pas autorisé à clôturer cet incident.'
            );
        }

        // Vérifier qu'un mémo du sous-gérant existe après l'intervention
        $intervention = $incident->interventions->last();

        $memoSousGerant = $incident->commentaires
            ->filter(function ($commentaire) use ($intervention) {

                return in_array(
                    $commentaire->user->role->nom_role,
                    ['Sous-gérant de station']
                )
                    && $intervention
                    && $commentaire->created_at >= $intervention->created_at;
            })
            ->count();

        if ($memoSousGerant === 0) {

            return back()->with(
                'error',
                'Impossible de clôturer : aucun retour du sous-gérant n\'a encore été reçu.'
            );
        }

        // Libérer le technicien
        if ($incident->technicien_assigne_id) {

            User::where(
                'id',
                $incident->technicien_assigne_id
            )->update([
                'disponibilite' => true
            ]);
        }

        // Remettre l'équipement en état fonctionnel
        Equipement::where(
            'id',
            $incident->equipement_id
        )->update([
            'etat' => 'fonctionnel'
        ]);

        // Clôturer l'incident
        $incident->update([
            'statut' => 'cloture'
        ]);

        historique::create([
            'action'      => 'Incident clôturé',
            'description' => 'Incident "' . $incident->titre . '" clôturé.',
            'date_action' => now(),
            'user_id'     => Auth::id(),
        ]);

        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'Incident clôturé avec succès.'
            );
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
            'titre'         => 'required|string|max:255',
            'description'   => 'required|string',
            'priorite'      => 'required|in:faible,eleve,critique',
            'domaine_id'    => 'required|exists:domaines,id',
            'station_id'    => 'required|exists:stations,id',
            'equipement_id' => [
                'required',
                'exists:equipements,id',
                function ($attribute, $value, $fail) {
                    $incidentActif = Incident::whereIn('statut', ['declare', 'assigne', 'en_cours'])
                        ->where('equipement_id', $value)
                        ->exists();
                    if ($incidentActif) {
                        $fail('Cet équipement a déjà un incident en cours.');
                    }
                }
            ],
            'pieces_jointes.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $validated['statut']           = 'declare';
        $validated['date_signalement'] = now();
        $validated['declarant_id']     = Auth::id();

        $incident = Incident::create($validated);

        // Historique
        historique::create([
            'action'      => 'Incident déclaré',
            'description' => 'Incident "' . $incident->titre . '" déclaré par ' . Auth::user()->prenom . '.',
            'date_action' => now(),
            'user_id'     => Auth::id(),
        ]);

        // Notifications in-app + email aux gestionnaires
        $managers = User::whereHas('role', fn($q) =>
            $q->whereIn('nom_role', ['Admin', 'Directeur Technicien', 'Responsable DT'])
        )->get();

        foreach ($managers as $manager) {
            NotifModel::create([
                'message'          => 'Nouvel incident déclaré : ' . $incident->titre,
                'statut'           => 'non_lue',
                'date_notification' => now(),
                'user_id'          => $manager->id,
            ]);
            try {
                $manager->notify(new IncidentDeclareNotification($incident->load('station')));
            } catch (\Exception $e) {}
        }

        // Sauvegarder les pièces jointes
        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $file) {
                $chemin = $file->store('pieces_jointes', 'public');

                Pieces::create([
                    'nom_fichier'    => $file->getClientOriginalName(),
                    'type_fichier'   => $file->getClientMimeType(),
                    'chemin_fichier' => $chemin,
                    'incident_id'    => $incident->id,
                    'source'         => 'declaration',
                ]);
            }
        }

        // Mettre l'équipement en panne
        Equipement::where('id', $validated['equipement_id'])
            ->update(['etat' => 'en_panne']);

        return redirect()->route('incidents.mes_incidents')
            ->with('success', 'Incident déclaré avec succès.');
    }

    // Détails d'un incident (agent)
    public function show(string $id)
    {
        $incident = Incident::with([
            'domaine',
            'station',
            'equipement',
            'declarant',
            'technicien',
            'piecesJointes',
            'commentaires.user',
        ])->findOrFail($id);

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
            'station_id'   => 'required|exists:stations,id',
            'equipement_id' => 'required|exists:equipements,id',
        ]);

        $incident->update($validated);

        return redirect()->route('incidents.mes_incidents')
            ->with('success', 'Incident modifié avec succès.');
    }

    // ==================== DIRECTION TECHNIQUE ====================

    // Liste de tous les incidents
    public function index(Request $request)
    {
        $query = Incident::with(['domaine', 'station', 'equipement', 'declarant', 'technicien'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('titre', 'like', "%{$s}%")
                                      ->orWhere('description', 'like', "%{$s}%"));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $incidents = $query->get();

        return view('layouts.incidentsDT.tous_les_incidents', compact('incidents'));
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

        return view('layouts.incidentsDT.assignation', compact('incident', 'intervenants'));
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

        // Historique
        historique::create([
            'action'      => 'Incident assigné',
            'description' => 'Incident "' . $incident->titre . '" assigné à ' . $technicien->prenom . ' ' . $technicien->nom . '.',
            'date_action' => now(),
            'user_id'     => Auth::id(),
        ]);

        // Notification in-app + email au technicien
        NotifModel::create([
            'message'          => 'L\'incident "' . $incident->titre . '" vous a été assigné.',
            'statut'           => 'non_lue',
            'date_notification' => now(),
            'user_id'          => $technicien->id,
        ]);
        try {
            $technicien->notify(new IncidentAssigneNotification($incident->load('station')));
        } catch (\Exception $e) {}

        return redirect()->route('incidents.index')
            ->with('success', 'Incident assigné avec succès.');
    }

    // Historique
    public function historique(string $id)
    {
        $incident    = Incident::with(['domaine', 'station', 'equipement', 'declarant'])->findOrFail($id);
        $historiques = Historique::where('user_id', Auth::id())->orderByDesc('date_action')->get();

        return view(' layouts.incidentsDT.historique', compact('incident', 'historiques'));
    }

    // Suppression
    public function destroy(string $id)
    {
        Incident::findOrFail($id)->delete();

        return redirect()->route('incidents.index')
            ->with('success', 'Incident supprimé.');
    }

    public function detailsRapport(string $id)
    {
        $incident = Incident::with([
            'domaine',
            'station',
            'equipement',
            'declarant',
            'technicien',
            'interventions.rapport',
            'piecesJointes',
            'commentaires.user',
        ])->findOrFail($id);

        $intervenants = User::whereHas(
            'role',
            fn($q) =>
            $q->where('nom_role', 'Technicien')
                ->orWhere('nom_role', 'Prestataire Externe')
        )
            ->where('domaine_id', $incident->domaine_id)
            ->where(function ($q) use ($incident) {
                $q->where('disponibilite', true)
                    ->orWhere('id', $incident->technicien_assigne_id);
            })
            ->orderBy('nom')
            ->get();

        return view('layouts.incidentsDT.details_rapport', compact('incident', 'intervenants'));
    }

    public function reassigner(Request $request, string $id)
    {
        $incident = Incident::findOrFail($id);

        if ($incident->statut !== 'non_resolu') {
            return redirect()->route('incidents.index')
                ->with('error', 'Action non autorisée.');
        }

        $request->validate([
            'technicien_assigne_id' => 'required|exists:users,id',
        ]);

        // Remettre le technicien actuel disponible
        if ($incident->technicien_assigne_id) {
            User::where('id', $incident->technicien_assigne_id)
                ->update(['disponibilite' => true]);
        }

        // Marquer le nouveau technicien comme occupé
        User::where('id', $request->technicien_assigne_id)
            ->update(['disponibilite' => false]);

        // Créer une nouvelle intervention
        Intervention::create([
            'date_debut'    => now(),
            'incident_id'   => $incident->id,
            'technicien_id' => $request->technicien_assigne_id,
        ]);

        $incident->update([
            'statut'                => 'assigne',
            'technicien_assigne_id' => $request->technicien_assigne_id,
        ]);

        $nouveauTech = User::find($request->technicien_assigne_id);
        historique::create([
            'action'      => 'Incident réassigné',
            'description' => 'Incident "' . $incident->titre . '" réassigné à ' . ($nouveauTech?->prenom . ' ' . $nouveauTech?->nom) . '.',
            'date_action' => now(),
            'user_id'     => Auth::id(),
        ]);

        if ($nouveauTech) {
            NotifModel::create([
                'message'          => 'L\'incident "' . $incident->titre . '" vous a été réassigné.',
                'statut'           => 'non_lue',
                'date_notification' => now(),
                'user_id'          => $nouveauTech->id,
            ]);
            try {
                $nouveauTech->notify(new IncidentAssigneNotification($incident->load('station')));
            } catch (\Exception $e) {}
        }

        return redirect()->route('incidents.index')
            ->with('success', 'Incident réassigné.');
    }
}