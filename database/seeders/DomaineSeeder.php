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
        // Domaines techniques spécifiques aux stations-services
        Domaine::updateOrCreate(['nom_domaine' => 'Volucompteurs & Pompes']);
        Domaine::updateOrCreate(['nom_domaine' => 'Cuves & Stockage Carburant']);
        Domaine::updateOrCreate(['nom_domaine' => 'Électricité & Énergie (Groupes Élec)']);
        Domaine::updateOrCreate(['nom_domaine' => 'Plomberie & Tuyauterie Haute Pression']);
        Domaine::updateOrCreate(['nom_domaine' => 'Sécurité Incendie & Environnement']);
        Domaine::updateOrCreate(['nom_domaine' => 'Génie Civil & Infrastructures']);
        Domaine::updateOrCreate(['nom_domaine' => 'Systèmes de Paiement & TPE']);
        Domaine::updateOrCreate(['nom_domaine' => 'Vidéosurveillance & Sécurité Électronique']);
    }
}
