<?php

namespace Database\Seeders;

use App\Models\Domaine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DomaineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Domaine::updateOrCreate(['nom_domaine' => 'Réseau']);
        Domaine::updateOrCreate(['nom_domaine' => 'Sécurité']);
        Domaine::updateOrCreate(['nom_domaine' => 'Maintenance']);
        Domaine::updateOrCreate(['nom_domaine' => 'Support']);
        Domaine::updateOrCreate(['nom_domaine' => 'Infrastructure']);
        Domaine::updateOrCreate(['nom_domaine' => 'Logiciels']);
    }
} 