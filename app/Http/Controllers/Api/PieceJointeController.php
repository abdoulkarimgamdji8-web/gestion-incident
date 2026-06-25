<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Pieces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PieceJointeController extends Controller
{
    public function store(Request $request, string $incidentId)
    {
        $incident = Incident::findOrFail($incidentId);

        $request->validate([
            'fichier' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'source'  => 'required|in:declaration,rapport',
        ]);

        $file   = $request->file('fichier');
        $chemin = $file->store('pieces_jointes', 'public');

        $piece = Pieces::create([
            'nom_fichier'    => $file->getClientOriginalName(),
            'type_fichier'   => $file->getClientMimeType(),
            'chemin_fichier' => $chemin,
            'incident_id'    => $incident->id,
            'source'         => $request->source,
        ]);

        return response()->json([
            'message'      => 'Fichier uploadé.',
            'piece_jointe' => $piece,
            'url'          => asset('storage/' . $chemin),
        ], 201);
    }

    public function destroy(Request $request, string $id)
    {
        $piece = Pieces::findOrFail($id);

        $incident = $piece->incident;
        if ($incident->declarant_id !== $request->user()->id
            && $incident->technicien_assigne_id !== $request->user()->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        Storage::disk('public')->delete($piece->chemin_fichier);
        $piece->delete();

        return response()->json(['message' => 'Fichier supprimé.']);
    }
}
