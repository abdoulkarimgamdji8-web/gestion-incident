<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Incident;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    public function index(string $incidentId)
    {
        $incident = Incident::findOrFail($incidentId);

        $commentaires = $incident->commentaires()
            ->with('user:id,nom,prenom')
            ->orderBy('created_at')
            ->get();

        return response()->json($commentaires);
    }

    public function store(Request $request, string $incidentId)
    {
        $incident = Incident::findOrFail($incidentId);

        $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        $commentaire = Commentaire::create([
            'contenu'     => $request->contenu,
            'incident_id' => $incident->id,
            'user_id'     => $request->user()->id,
        ]);

        return response()->json($commentaire->load('user:id,nom,prenom'), 201);
    }

    public function destroy(Request $request, string $id)
    {
        $commentaire = Commentaire::findOrFail($id);

        if ($commentaire->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}
