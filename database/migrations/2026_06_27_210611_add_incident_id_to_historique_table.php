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
        Schema::table('historique', function (Blueprint $table) {
            $table->foreignId('incident_id')
                ->nullable()
                ->after('user_id')
                ->constrained('incident')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('historique', function (Blueprint $table) {
            $table->dropForeign(['incident_id']);
            $table->dropColumn('incident_id');
        });
    }
};