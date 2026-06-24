<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plain password shown here intentionally for your records.
        $plainPassword = '12345678';

        $adminRole = Role::firstWhere('nom_role', 'Admininstrateur systeme');    
        $technicianRole = Role::firstWhere('nom_role', 'Technicien');

        // Create Admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make($plainPassword),
                'numero' => '0612345670',
                'date' => now()->subYears(30),
                'statut' => 1,
                'role_id' => $adminRole->id,
                'remember_token' => Str::random(10),
            ]
        );

        // Create Technician user
        User::updateOrCreate(
            ['email' => 'technicien@example.com'],
            [
                'nom' => 'Technicien',
                'prenom' => 'Test',
                'email' => 'technicien@example.com',
                'password' => Hash::make($plainPassword),
                'numero' => '0612345671',
                'date' => now()->subYears(30),
                'statut' => 1,
                'role_id' => 1,
                'remember_token' => Str::random(10),
            ]
        );
    }
}