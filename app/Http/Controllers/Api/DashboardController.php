<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard accessible by all authenticated users.
     */
    public function index(Request $request)
    {
        $user = $request->user()->load('role:id,name');

        return response()->json([
            'message' => 'Bienvenue sur le dashboard',
            'user' => $user->only(['id', 'nom', 'prenom', 'email', 'role_id']),
            'role' => $user->role?->name,
        ]);
    }

    /**
     * Admin-only endpoint.
     */
    public function adminPanel(Request $request)
    {
        return response()->json([
            'message' => 'Panneau d\'administration',
            'data' => [],
        ]);
    }

    /**
     * Technician-only endpoint.
     */
    public function technicianPanel(Request $request)
    {
        return response()->json([
            'message' => 'Panneau technicien',
            'data' => [],
        ]);
    }
}
