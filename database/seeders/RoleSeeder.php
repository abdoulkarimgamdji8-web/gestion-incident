<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Role::updateOrCreate(['nom_role' => 'Administrateur système']);
        Role::updateOrCreate(['nom_role' => 'Directeur maintenance']);
        Role::updateOrCreate(['nom_role' => 'Responsable maintenance']);
        Role::updateOrCreate(['nom_role' => 'Technicien']);
        Role::updateOrCreate(['nom_role' => 'Prestataire Externe']);
        Role::updateOrCreate(['nom_role' => 'Sous-gérant de station']);
    }
}
