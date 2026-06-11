<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Intervention;
use App\Models\Rapport;
use App\Models\Pieces;
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
            ->whereIn('statut', ['assigne', 'en_cours', 'resolu', 'cloture'])
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

        return view('layouts.interventions.rapport', compact('incident'));
    }

    // Soumettre rapport + pièces jointes → statut résolu
    public function storeRapport(Request $request, string $id)
    {
        $incident = Incident::with('interventions')->findOrFail($id);

        if ($incident->technicien_assigne_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'contenu'       => 'required|string',
            'resultat'      => 'required|string|max:255',
            'observation'   => 'nullable|string',
            'pieces_jointes.*' => '|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Mettre à jour l'intervention
        $intervention = $incident->interventions()->latest()->first();
        $intervention->update([
            'date_fin'    => now(),
            'resultat'    => $request->resultat,
            'observation' => $request->observation,
        ]);

        // Créer le rapport
        Rapport::create([
            'contenu'         => $request->contenu,
            'date_rapport'    => now(),
            'intervention_id' => $intervention->id,
        ]);

        // Pièces jointes
        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $file) {
                $chemin = $file->store('pieces_jointes', 'public');

                Pieces::create([
                    'nom_fichier'   => $file->getClientOriginalName(),
                    'type_fichier'  => $file->getClientMimeType(),
                    'chemin_fichier' => $chemin,
                    'incident_id'   => $incident->id,
                    'source'        => 'rapport',
                ]);
            }
        }

        // Changer statut → résolu
        $incident->update(['statut' => 'resolu']);

        return redirect()->route('interventions.mes_interventions')
            ->with('success', 'Rapport soumis. Incident marqué comme résolu.');
    }
}