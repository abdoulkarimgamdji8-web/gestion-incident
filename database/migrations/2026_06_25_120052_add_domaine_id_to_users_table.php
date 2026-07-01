<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajoute la clé étrangère nullable juste après l'ancienne colonne 'domaine'
            $table->foreignId('domaine_id')
                  ->nullable()
                  ->after('domaine')
                  ->constrained('domaines')
                  ->nullOnDelete(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprime la contrainte de clé étrangère puis la colonne
            $table->dropForeign(['domaine_id']);
            $table->dropColumn('domaine_id');
        });
    }
};
