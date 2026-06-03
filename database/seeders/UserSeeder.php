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

        $adminRole = Role::firstWhere('nom_role', 'Admin');

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make($plainPassword),
                'numero' => '0612345678',
                'date' => now()->subYears(30),
                'statut' => '1',
                'role_id' => 1,
                'remember_token' => Str::random(10),
            ]
        );
    }
}