<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Incident;
use App\Models\User;
use App\Models\historique;
use App\Models\notification as NotifModel;
use App\Notifications\IncidentDeclareNotification;
use App\Notifications\IncidentAssigneNotification;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role->nom_role;

        $query = Incident::with(['domaine', 'station', 'equipement', 'declarant', 'technicien'])
            ->orderByDesc('created_at');

        if (in_array($role, ['Technicien', 'Prestataire Externe'])) {
            $query->where('technicien_assigne_id', $user->id);
        } elseif ($role === 'Agent Station') {
            $query->where('declarant_id', $user->id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('titre', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%"));
        }
        if ($request->filled('statut'))   $query->where('statut', $request->statut);
        if ($request->filled('priorite')) $query->where('priorite', $request->priorite);

        return response()->json($query->paginate(15));
    }

    public function show(string $id)
    {
        $incident = Incident::with([
            'domaine', 'station', 'equipement', 'declarant',
            'technicien', 'interventions.rapport', 'piecesJointes', 'commentaires.user',
        ])->findOrFail($id);

        return response()->json($incident);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'         => 'required|string|max:255',
            'description'   => 'required|string',
            'priorite'      => 'required|in:faible,eleve,critique',
            'domaine_id'    => 'required|exists:domaines,id',
            'station_id'    => 'required|exists:stations,id',
            'equipement_id' => 'required|exists:equipements,id',
        ]);

        $validated['statut']           = 'declare';
        $validated['date_signalement'] = now();
        $validated['declarant_id']     = $request->user()->id;

        $incident = Incident::create($validated);

        Equipement::where('id', $validated['equipement_id'])->update(['etat' => 'en_panne']);

        historique::create([
            'action'      => 'Incident déclaré (API)',
            'description' => 'Incident "' . $incident->titre . '" déclaré via API.',
            'date_action' => now(),
            'user_id'     => $request->user()->id,
        ]);

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
            try { $manager->notify(new IncidentDeclareNotification($incident->load('station'))); }
            catch (\Exception $e) {}
        }

        return response()->json($incident->load('domaine', 'station', 'equipement'), 201);
    }

    public function assign(Request $request, string $id)
    {
        $incident = Incident::findOrFail($id);

        $request->validate([
            'technicien_assigne_id' => 'required|exists:users,id',
        ]);

        $technicien = User::findOrFail($request->technicien_assigne_id);
        $technicien->update(['disponibilite' => false]);

        $incident->update([
            'technicien_assigne_id' => $request->technicien_assigne_id,
            'statut'                => 'assigne',
        ]);

        historique::create([
            'action'      => 'Incident assigné (API)',
            'description' => 'Incident "' . $incident->titre . '" assigné à ' . $technicien->prenom . '.',
            'date_action' => now(),
            'user_id'     => $request->user()->id,
        ]);

        NotifModel::create([
            'message'          => 'L\'incident "' . $incident->titre . '" vous a été assigné.',
            'statut'           => 'non_lue',
            'date_notification' => now(),
            'user_id'          => $technicien->id,
        ]);
        try { $technicien->notify(new IncidentAssigneNotification($incident->load('station'))); }
        catch (\Exception $e) {}

        return response()->json(['message' => 'Incident assigné.', 'incident' => $incident]);
    }

    public function cloturer(Request $request, string $id)
    {
        $incident = Incident::with(['commentaires.user.role', 'interventions'])->findOrFail($id);

        if ($incident->statut !== 'resolu') {
            return response()->json(['message' => 'Seul un incident résolu peut être clôturé.'], 422);
        }

        if ($incident->technicien_assigne_id) {
            User::where('id', $incident->technicien_assigne_id)->update(['disponibilite' => true]);
        }
        Equipement::where('id', $incident->equipement_id)->update(['etat' => 'fonctionnel']);

        $incident->update(['statut' => 'cloture']);

        historique::create([
            'action'      => 'Incident clôturé (API)',
            'description' => 'Incident "' . $incident->titre . '" clôturé via API.',
            'date_action' => now(),
            'user_id'     => $request->user()->id,
        ]);

        return response()->json(['message' => 'Incident clôturé.']);
    }
}
