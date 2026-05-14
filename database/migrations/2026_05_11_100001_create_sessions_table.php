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
            CREATE TABLE sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload TEXT NOT NULL,
                last_activity INTEGER NOT NULL
            )
        ");

        DB::statement("CREATE INDEX sessions_user_id_index ON sessions(user_id)");
        DB::statement("CREATE INDEX sessions_last_activity_index ON sessions(last_activity)");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS sessions CASCADE");
    }
};
