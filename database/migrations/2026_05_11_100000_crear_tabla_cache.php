<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        DB::statement("
            CREATE TABLE cache (
                key VARCHAR(255) PRIMARY KEY,
                value TEXT NOT NULL,
                expiration INTEGER NOT NULL
            )
        ");

        DB::statement("
            CREATE TABLE cache_locks (
                key VARCHAR(255) PRIMARY KEY,
                owner VARCHAR(255) NOT NULL,
                expiration INTEGER NOT NULL
            )
        ");

        DB::statement("CREATE INDEX cache_expiration_index ON cache(expiration)");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS cache_locks");
        DB::statement("DROP TABLE IF EXISTS cache");
    }
};
