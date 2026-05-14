<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE propiedades ADD COLUMN certificado_energetico_archivo VARCHAR(500) NULL");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE propiedades DROP COLUMN IF EXISTS certificado_energetico_archivo");
    }
};
