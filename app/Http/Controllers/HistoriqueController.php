<?php

namespace App\Http\Controllers;

use App\Models\Historique;

class HistoriqueController extends Controller
{
    public function index()
    {
        $historiques = Historique::with(['user.role', 'incident'])
            ->orderByDesc('date_action')
            ->paginate(10);

        return view('layouts.historiques.logs', compact('historiques'));
    }
}