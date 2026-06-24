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
        Schema::table('incident', function (Blueprint $table) {

            $table->string('motif_attente')
                  ->nullable()
                  ->after('statut');

            $table->text('description_attente')
                  ->nullable()
                  ->after('motif_attente');

            $table->date('date_reprise_prevue')
                  ->nullable()
                  ->after('description_attente');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident', function (Blueprint $table) {

            $table->dropColumn([
                'motif_attente',
                'description_attente',
                'date_reprise_prevue'
            ]);

        });
    }
};