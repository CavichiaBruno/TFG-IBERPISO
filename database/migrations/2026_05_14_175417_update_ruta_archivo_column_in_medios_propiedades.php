<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::table('medios_propiedades', function (Blueprint $table) {
            $table->string('ruta_archivo', 2048)->change();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('medios_propiedades', function (Blueprint $table) {
            $table->string('ruta_archivo', 500)->change();
        });
    }
};
