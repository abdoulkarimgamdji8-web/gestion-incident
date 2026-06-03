<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Domaine;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.liste', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('nom_role')->get();
        $domaines = Domaine::orderBy('nom_domaine')->get();
        return view('users.ajouter', compact('roles', 'domaines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'numero' => 'required|digits_between:1,9|unique:users,numero',
            'password' => 'required|string|min:8|max:8',
            'role_id' => 'required|exists:roles,id',
            'domaine_id' => 'nullable|exists:domaines,id',
        ]);

        $user = new User();
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->numero = $validated['numero'];
        $user->date = now();
        $user->statut = true;
        $user->role_id = $validated['role_id'];

        if (!empty($validated['domaine_id'])) {
            $user->domaine_id = $validated['domaine_id'];
        }

        $selectedRole = Role::find($validated['role_id']);
        if ($selectedRole && (str_contains(strtolower($selectedRole->nom_role), 'Technicien') || str_contains(strtolower($selectedRole->nom_role), 'Prestataire Externe'))) {
            $user->disponibilite = true;
        } else {
            $user->disponibilite = null;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}