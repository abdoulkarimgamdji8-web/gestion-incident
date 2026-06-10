<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::orderBy('nom')->get();
        return view('layouts.stations.liste', compact('stations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stations.ajouter');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'statut' => 'sometimes|boolean',
        ]);

        Station::create([
            'nom' => $validated['nom'],
            'ville' => $validated['ville'],
            'zone' => $validated['zone'],
            'statut' => $request->has('statut'),
        ]);

        return redirect()->route('stations.index')->with('success', 'Station ajoutée avec succès.');
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
        $station = Station::findOrFail($id);
        return view('layouts.stations.modifier', compact('station'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $station = Station::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'statut' => 'sometimes|boolean',
        ]);

            $hasChanges = $station->nom !== $validated['nom'] ||
                        $station->ville !== $validated['ville'] ||
                        $station->zone !== $validated['zone'] ||
                        (bool) $station->statut !== (bool) $request->has('statut');

        if (!$hasChanges) {
            return back()->with('info', 'Aucune modification détectée. Veuillez cliquer sur Retour pour revenir en arrière.')->withInput();
        }

        $station->update([
            'nom' => $validated['nom'],
            'ville' => $validated['ville'],
            'zone' => $validated['zone'],
            'statut' => $request->has('statut'),
        ]);

        return redirect()->route('stations.index')->with('success', 'Station mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $station = Station::findOrFail($id);
        $station->delete();

        return redirect()->route('stations.index')->with('success', 'Station supprimée avec succès.');
    }

    public function toggleStatus(string $id)
    {
        $station = Station::findOrFail($id);
        $station->statut = !$station->statut;
        $station->save();

        return redirect()->route('stations.index')->with('success', 'Statut de la station mis à jour avec succès');
    }
}