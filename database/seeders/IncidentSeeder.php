<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On s'assure d'avoir au moins un utilisateur pour les techniciens assignés
        $technicienId = DB::table('users')->first()?->id ?? null;

        // Modèles d'incidents types selon l'équipement
        $modelesIncidents = [
            'Pompe Super 1' => [
                ['titre' => 'Fuite pistolet Super', 'desc' => 'Léger suintement au niveau du pistolet de distribution.', 'prio' => 'faible'],
                ['titre' => 'Panne carte électronique', 'desc' => 'L\'écran de la pompe ne s\'allume plus.', 'prio' => 'eleve'],
            ],
            'Pompe Gazole 1' => [
                ['titre' => 'Débit lent gazole', 'desc' => 'Le remplissage prend anormalement du temps.', 'prio' => 'faible'],
            ],
            'Pompe Super 2' => [
                ['titre' => 'Blocage mécanique', 'desc' => 'Le pistolet reste bloqué en position ouverte.', 'prio' => 'critique'],
            ],
            'Pompe Gazole 2' => [
                ['titre' => 'Erreur d\'indexation', 'desc' => 'Le compteur de litres ne transmet plus les données.', 'prio' => 'eleve'],
            ],
            'Cuve Super 20000L' => [
                ['titre' => 'Présence d\'eau suspectée', 'desc' => 'Alerte de la sonde de fond de cuve indiquant de l\'eau.', 'prio' => 'critique'],
            ],
            'Cuve Gazole 30000L' => [
                ['titre' => 'Dysfonctionnement jauge', 'desc' => 'La jauge électronique renvoie une valeur fixe.', 'prio' => 'faible'],
            ],
            'Groupe Électrogène 50KVA' => [
                ['titre' => 'Baisse de tension', 'desc' => 'Le groupe démarre mais ne fournit pas assez de puissance.', 'prio' => 'eleve'],
            ],
            'Compresseur d\'air' => [
                ['titre' => 'Manomètre cassé', 'desc' => 'La vitre du manomètre est brisée, lecture impossible.', 'prio' => 'faible'],
            ],
            'Extincteur CO2 50kg' => [
                ['titre' => 'Baisse de pression', 'desc' => 'L\'aiguille de l\'extincteur est hors de la zone verte.', 'prio' => 'eleve'],
            ],
            'Système de Vidéosurveillance' => [
                ['titre' => 'Caméra zone pompe HS', 'desc' => 'Perte de signal vidéo sur la caméra extérieure numéro 2.', 'prio' => 'eleve'],
            ],
        ];

        $statuts = ['declare', 'assigne', 'en_cours', 'resolu', 'cloture'];

        // Récupérer les stations
        $stations = DB::table('stations')->get();

        $data = [];

        foreach ($stations as $station) {
            // Récupérer les équipements de cette station spécifique
            $equipements = DB::table('equipements')
                ->where('station_id', $station->id)
                ->get();

            // Générer exactement 10 incidents par station
            for ($i = 0; $i < 10; $i++) {
                // Sélectionner un équipement au hasard parmi les 10 de la station
                $equipement = $equipements->random();

                // Trouver des modèles d'incidents associés à cet équipement
                $incidentsPossibles = $modelesIncidents[$equipement->nom] ?? [
                    ['titre' => 'Anomalie équipement', 'desc' => 'Problème général signalé sur cet équipement.', 'prio' => 'faible']
                ];
                
                $modeleSelectionne = $incidentsPossibles[array_rand($incidentsPossibles)];

                // Définition aléatoire du statut
                $statut = $statuts[array_rand($statuts)];

                // Logique cohérente pour le technicien assigné selon le statut
                $assigneId = in_array($statut, ['assigne', 'en_cours', 'resolu', 'cloture']) ? $technicienId : null;

                // Date de signalement aléatoire dans les 30 derniers jours
                $dateSignalement = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));

                $data[] = [
                    'titre' => $modeleSelectionne['titre'],
                    'description' => $modeleSelectionne['desc'],
                    'statut' => $statut,
                    'priorite' => $modeleSelectionne['prio'],
                    'date_signalement' => $dateSignalement,
                    'technicien_assigne_id' => $assigneId,
                    'station_id' => $station->id,
                    'equipement_id' => $equipement->id,
                    'domaine_id' => null, // Optionnel selon votre migration
                    'created_at' => $dateSignalement,
                    'updated_at' => $statut === 'cloture' || $statut === 'resolu' ? $dateSignalement->addDays(1) : Carbon::now(),
                ];
            }
        }

        // Insertion par blocs de 100 pour optimiser les performances
        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('incidents')->insert($chunk);
        }
    }
}
