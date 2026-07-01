<?php

namespace App\Helpers;

use App\Models\Historique;
use Illuminate\Support\Facades\Auth;

class HistoriqueHelper
{
    public static function log(string $action, string $description, int $incidentId): void
    {
        Historique::create([
            'action'      => $action,
            'description' => $description,
            'date_action' => now(),
            'user_id'     => Auth::id(),
            'incident_id' => $incidentId,
        ]);
    }
}