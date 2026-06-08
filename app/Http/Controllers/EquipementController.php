<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipement;
use App\Models\Station;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::with('station')->orderBy('nom')->get();

        return view('layouts.equipements.liste', compact('equipements'));
    }

    public function create()
    {
        $stations = Station::orderBy('nom')->get();

        return view('layouts.equipements.ajouter', compact('stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'etat' => 'required|in:fonctionnel,en_panne,critique',
            'station_id' => 'required|exists:stations,id',
        ]);

        Equipement::create($validated);

        return redirect()->route('equipements.index')->with('success', 'Équipement ajouté avec succès.');
    }

    public function edit(string $id)
    {
        $equipement = Equipement::findOrFail($id);
        $stations = Station::orderBy('nom')->get();

        return view('layouts.equipements.modifier', compact('equipement', 'stations'));
    }

    public function update(Request $request, string $id)
    {
        $equipement = Equipement::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'etat' => 'required|in:fonctionnel,en_panne,critique',
            'station_id' => 'required|exists:stations,id',
        ]);

        $equipement->update($validated);

        return redirect()->route('equipements.index')->with('success', 'Équipement mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $equipement = Equipement::findOrFail($id);
        $equipement->delete();

        return redirect()->route('equipements.index')->with('success', 'Équipement supprimé avec succès.');
    }
}