<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    public function store(Request $request, string $id)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        Commentaire::create([
            'contenu'     => $request->contenu,
            'incident_id' => $id,
            'user_id'     => Auth::id(),
        ]);

        $role = Auth::user()->role->nom_role;

        if (in_array($role, ['Agent Station', 'Sous-gérant de station'])) {
            return redirect()->route('incidents.show', $id)
                ->with('success', 'Mémo envoyé au Directeur Technique.');
        }

        return redirect()->route('incidents.details_rapport', $id)
            ->with('success', 'Commentaire ajouté.');
    }
}