<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Intervention;
use App\Models\Rapport;
use App\Models\Pieces;
use App\Models\User;
use App\Models\historique;
use App\Models\notification as NotifModel;
use App\Notifications\IncidentResoluNotification;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function intervenir(Request $request, string $id)
    {
        $incident = Incident::findOrFail($id);

        if ($incident->technicien_assigne_id !== $request->user()->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }
        if ($incident->statut !== 'assigne') {
            return response()->json(['message' => 'Action non autorisée.'], 422);
        }

        Intervention::create([
            'date_debut'    => now(),
            'incident_id'   => $incident->id,
            'technicien_id' => $request->user()->id,
        ]);
        $incident->update(['statut' => 'en_cours']);

        historique::create([
            'action'      => 'Intervention démarrée (API)',
            'description' => 'Intervention démarrée sur l\'incident "' . $incident->titre . '".',
            'date_action' => now(),
            'user_id'     => $request->user()->id,
        ]);

        return response()->json(['message' => 'Intervention démarrée.']);
    }

    public function storeRapport(Request $request, string $id)
    {
        $incident = Incident::with('interventions')->findOrFail($id);

        if ($incident->technicien_assigne_id !== $request->user()->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $intervention = $incident->interventions()->latest()->first();

        if ($request->resultat_intervention === 'en_attente') {
            $request->validate([
                'motif_attente'       => 'required|string',
                'description_attente' => 'required|string',
                'date_reprise_prevue' => 'required|date',
            ]);
            $incident->update([
                'statut'              => 'en_attente',
                'motif_attente'       => $request->motif_attente,
                'description_attente' => $request->description_attente,
                'date_reprise_prevue' => $request->date_reprise_prevue,
            ]);
            historique::create([
                'action'      => 'Incident en attente (API)',
                'description' => 'Incident "' . $incident->titre . '" mis en attente.',
                'date_action' => now(),
                'user_id'     => $request->user()->id,
            ]);
            return response()->json(['message' => 'Incident mis en attente.']);
        }

        $request->validate([
            'contenu'     => 'required|string',
            'resultat'    => 'required|string|max:255',
            'observation' => 'nullable|string',
            'resultat_intervention' => 'required|in:resolu,non_resolu',
        ]);

        $intervention->update([
            'date_fin'    => now(),
            'resultat'    => $request->resultat,
            'observation' => $request->observation,
        ]);

        Rapport::updateOrCreate(
            ['intervention_id' => $intervention->id],
            ['contenu' => $request->contenu, 'date_rapport' => now()]
        );

        $incident->update([
            'statut'              => $request->resultat_intervention,
            'motif_attente'       => null,
            'description_attente' => null,
            'date_reprise_prevue' => null,
        ]);

        if ($request->resultat_intervention === 'non_resolu') {
            User::find($incident->technicien_assigne_id)?->update(['disponibilite' => true]);
        }

        historique::create([
            'action'      => $request->resultat_intervention === 'resolu' ? 'Incident résolu (API)' : 'Incident non résolu (API)',
            'description' => 'Rapport soumis pour "' . $incident->titre . '".',
            'date_action' => now(),
            'user_id'     => $request->user()->id,
        ]);

        if ($incident->declarant_id) {
            NotifModel::create([
                'message'          => 'L\'incident "' . $incident->titre . '" est ' . ($request->resultat_intervention === 'resolu' ? 'résolu' : 'non résolu') . '.',
                'statut'           => 'non_lue',
                'date_notification' => now(),
                'user_id'          => $incident->declarant_id,
            ]);
            try {
                User::find($incident->declarant_id)?->notify(new IncidentResoluNotification($incident, $request->resultat_intervention));
            } catch (\Exception $e) {}
        }

        return response()->json(['message' => 'Rapport soumis.']);
    }
}
