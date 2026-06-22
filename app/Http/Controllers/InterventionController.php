<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Intervention;
use App\Models\Rapport;
use App\Models\Pieces;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InterventionController extends Controller
{
    // Liste des interventions de l'intervenant connecté
    public function mesInterventions()
    {
        $interventions = Incident::with(['domaine', 'station', 'equipement'])
            ->where('technicien_assigne_id', Auth::id())
            ->whereIn('statut', ['assigne', 'en_cours', 'en_attente', 'resolu', 'non_resolu', 'cloture'])
            ->orderByDesc('updated_at')
            ->get();

        return view('layouts.interventions.mes_interventions', compact('interventions'));
    }

    // Changer statut assigne → en_cours
    public function intervenir(string $id)
    {
        $incident = Incident::findOrFail($id);

        // Vérifier que c'est bien son incident
        if ($incident->technicien_assigne_id !== Auth::id()) {
            abort(403);
        }

        if ($incident->statut !== 'assigne') {
            return redirect()->route('interventions.mes_interventions')
                ->with('error', 'Action non autorisée.');
        }

        // Créer l'intervention
        Intervention::create([
            'date_debut'   => now(),
            'incident_id'  => $incident->id,
            'technicien_id' => Auth::id(),
        ]);

        $incident->update(['statut' => 'en_cours']);

        return redirect()->route('interventions.mes_interventions')
            ->with('success', 'Intervention démarrée.');
    }

    // Formulaire rapport + pièces jointes
    public function showRapport(string $id)
    {
        $incident = Incident::with(['domaine', 'station', 'equipement', 'interventions'])
            ->findOrFail($id);

        if ($incident->technicien_assigne_id !== Auth::id()) {
            abort(403);
        }

        if ($incident->statut !== 'en_cours') {
            return redirect()->route('interventions.mes_interventions')
                ->with('error', 'Action non autorisée.');
        }

        $intervention = $incident->interventions()->latest()->first();
        $rapportExistant = $intervention?->rapport;

        return view('layouts.interventions.rapport', compact('incident', 'rapportExistant'));
    }

    // Soumettre rapport + pièces jointes → statut résolu
    public function storeRapport(Request $request, string $id)
    {
        $incident = Incident::with('interventions')->findOrFail($id);

        if ($incident->technicien_assigne_id !== Auth::id()) {
            abort(403);
        }

        $intervention = $incident->interventions()->latest()->first();

        /*
    |--------------------------------------------------------------------------
    | CAS : EN ATTENTE
    |--------------------------------------------------------------------------
    */
        if ($request->resultat_intervention === 'en_attente') {

            $request->validate([
                'motif_attente' => 'required|string',
                'description_attente' => 'required|string',
                'date_reprise_prevue' => 'required|date',
            ]);

            $incident->update([
                'statut' => 'en_attente',
                'motif_attente' => $request->motif_attente,
                'description_attente' => $request->description_attente,
                'date_reprise_prevue' => $request->date_reprise_prevue,
            ]);

            return redirect()
                ->route('interventions.mes_interventions')
                ->with(
                    'success',
                    'Intervention mise en attente avec succès.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | CAS : RESOLU OU NON RESOLU
    |--------------------------------------------------------------------------
    */
        $request->validate([
            'contenu' => 'required|string',
            'resultat' => 'required|string|max:255',
            'observation' => 'nullable|string',
            'pieces_jointes.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $intervention->update([
            'date_fin' => now(),
            'resultat' => $request->resultat,
            'observation' => $request->observation,
        ]);

        Rapport::updateOrCreate(
            [
                'intervention_id' => $intervention->id
            ],
            [
                'contenu' => $request->contenu,
                'date_rapport' => now(),
            ]
        );

        // Pièces jointes
        if ($request->hasFile('pieces_jointes')) {

            foreach ($request->file('pieces_jointes') as $file) {

                $chemin = $file->store(
                    'pieces_jointes',
                    'public'
                );

                Pieces::create([
                    'nom_fichier' => $file->getClientOriginalName(),
                    'type_fichier' => $file->getClientMimeType(),
                    'chemin_fichier' => $chemin,
                    'incident_id' => $incident->id,
                    'source' => 'rapport',
                ]);
            }
        }

        // Mise à jour statut incident
        $incident->update([
            'statut' => $request->resultat_intervention,
            'motif_attente' => null,
            'description_attente' => null,
            'date_reprise_prevue' => null,
        ]);

        // Libérer le technicien uniquement si NON RESOLU
        if ($request->resultat_intervention === 'non_resolu') {

            $technicien = User::find(
                $incident->technicien_assigne_id
            );

            if ($technicien) {
                $technicien->update([
                    'disponibilite' => true
                ]);
            }
        }

        $messages = [
            'resolu' =>
            'Rapport soumis. Incident marqué comme résolu.',

            'non_resolu' =>
            'Rapport soumis. Incident marqué comme non résolu.',
        ];

        return redirect()
            ->route('interventions.mes_interventions')
            ->with(
                'success',
                $messages[$request->resultat_intervention]
            );
    }

    public function reprendre(string $id)
    {
        $incident = Incident::findOrFail($id);
        $incident->update(['statut' => 'en_cours']);
        return redirect()->route('incidents.rapport', $incident->id);
    }
}