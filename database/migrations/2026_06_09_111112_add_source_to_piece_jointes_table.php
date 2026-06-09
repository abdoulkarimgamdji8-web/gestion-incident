public function up(): void
{
    Schema::table('pieces_jointes', function (Blueprint $table) {
        $table->enum('source', ['declaration', 'rapport'])
              ->default('declaration')
              ->after('incident_id');
    });
}

public function down(): void
{
    Schema::table('pieces_jointes', function (Blueprint $table) {
        $table->dropColumn('source');
    });
}