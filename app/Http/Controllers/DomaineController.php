<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domaine;

class DomaineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $domaines = Domaine::orderBy('nom_domaine')->get();
        return view('layouts.domaines.liste', compact('domaines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.domaines.ajouter');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_domaine' => 'required|string|max:255|unique:domaines,nom_domaine',
        ]);

        Domaine::create([
            'nom_domaine' => $validated['nom_domaine'],
        ]);

        return redirect()->route('domaines.index')->with('success', 'Domaine ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $domaine = Domaine::findOrFail($id);
        return view('layouts.domaines.modifier', compact('domaine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $domaine = Domaine::findOrFail($id);

        $validated = $request->validate([
            'nom_domaine' => 'required|string|max:255|unique:domaines,nom_domaine,' . $domaine->id,
        ]);

        $domaine->nom_domaine = $validated['nom_domaine'];
        $domaine->save();

        return redirect()->route('domaines.index')->with('success', 'Domaine mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $domaine = Domaine::findOrFail($id);
        $domaine->delete();

        return redirect()->route('domaines.index')->with('success', 'Domaine supprimé avec succès.');
    }
}