<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE incidents MODIFY COLUMN statut ENUM('declare','assigne','en_cours','resolu','cloture','en_attente','non_resolu') NOT NULL DEFAULT 'declare'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE incidents MODIFY COLUMN statut ENUM('declare','assigne','en_cours','resolu','cloture') NOT NULL DEFAULT 'declare'");
    }
};
