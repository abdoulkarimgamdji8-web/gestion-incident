<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stations = [
            // Stations à Douala
            ['nom' => 'Station GULFIN Bali', 'ville' => 'Douala', 'zone' => 'Bali', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Akwa Nord', 'ville' => 'Douala', 'zone' => 'Akwa Nord', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Ndogsimbi', 'ville' => 'Douala', 'zone' => 'Ndogsimbi', 'statut' => 'active'],
            ['nom' => 'Station GULFIN PK 14', 'ville' => 'Douala', 'zone' => 'PK 14', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Bonendale', 'ville' => 'Douala', 'zone' => 'Bonendale', 'statut' => 'active'],
            ['nom' => 'Station GULFIN NYALLA', 'ville' => 'Douala', 'zone' => 'Nyalla', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Malangue', 'ville' => 'Douala', 'zone' => 'Malangue', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Soboum 1', 'ville' => 'Douala', 'zone' => 'Soboum', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Soboum 2', 'ville' => 'Douala', 'zone' => 'Soboum', 'statut' => 'active'],

            // Stations à Yaoundé
            ['nom' => 'Station GULFIN Mvog-Betsi', 'ville' => 'Yaoundé', 'zone' => 'Mvog-Betsi', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Etoug-Ebe', 'ville' => 'Yaoundé', 'zone' => 'Etoug-Ebe', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Obobogo 1', 'ville' => 'Yaoundé', 'zone' => 'Obobogo', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Obobogo 2', 'ville' => 'Yaoundé', 'zone' => 'Obobogo', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Ekié', 'ville' => 'Yaoundé', 'zone' => 'Ekié', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Nsam Basilique', 'ville' => 'Yaoundé', 'zone' => 'Nsam', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Etoudi', 'ville' => 'Yaoundé', 'zone' => 'Etoudi', 'statut' => 'active'],

            // Autres localités
            ['nom' => 'Station GULFIN Bifaga', 'ville' => 'Bifaga', 'zone' => 'Centre', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Bare-Bakem', 'ville' => 'Baré-Bakem', 'zone' => 'Moungo', 'statut' => 'active'],
            ['nom' => 'Station GULFIN Edéa', 'ville' => 'Edéa', 'zone' => 'Sanaga-Maritime', 'statut' => 'active'],
        ];

        foreach ($stations as $station) {
            DB::table('stations')->insert([
                'nom' => $station['nom'],
                'ville' => $station['ville'],
                'zone' => $station['zone'],
                'statut' => $station['statut'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
