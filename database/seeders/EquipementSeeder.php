<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des types d'équipements standards dans une station Gulfin
        $modelesEquipements = [
            ['nom' => 'Pompe Super 1', 'type' => 'Volucompteur'],
            ['nom' => 'Pompe Gazole 1', 'type' => 'Volucompteur'],
            ['nom' => 'Pompe Super 2', 'type' => 'Volucompteur'],
            ['nom' => 'Pompe Gazole 2', 'type' => 'Volucompteur'],
            ['nom' => 'Cuve Super 20000L', 'type' => 'Stockage'],
            ['nom' => 'Cuve Gazole 30000L', 'type' => 'Stockage'],
            ['nom' => 'Groupe Électrogène 50KVA', 'type' => 'Énergie'],
            ['nom' => 'Compresseur d\'air', 'type' => 'Entretien'],
            ['nom' => 'Extincteur CO2 50kg', 'type' => 'Sécurité'],
            ['nom' => 'Système de Vidéosurveillance', 'type' => 'Sécurité'],
        ];

        // États possibles selon votre énumération
        $etats = ['fonctionnel', 'en_panne', 'critique'];

        // Récupérer les IDs de toutes les stations existantes
        $stationIds = DB::table('stations')->pluck('id');

        $data = [];

        foreach ($stationIds as $stationId) {
            foreach ($modelesEquipements as $index => $modele) {
                // Attribution d'un état aléatoire (généralement fonctionnel)
                $rand = rand(1, 100);
                $etat = 'fonctionnel';
                if ($rand > 95) {
                    $etat = 'en_panne';
                } elseif ($rand > 90) {
                    $etat = 'critique';
                }

                $data[] = [
                    'nom' => $modele['nom'],
                    'type' => $modele['type'],
                    'etat' => $etat,
                    'station_id' => $stationId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertion par blocs pour optimiser la base de données
        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('equipements')->insert($chunk);
        }
    }
}
