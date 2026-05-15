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
        if (!Schema::hasColumn('propiedades', 'certificado_energetico_archivo')) {
            Schema::table('propiedades', function (Blueprint $table) {
                $table->string('certificado_energetico_archivo')->nullable()->after('activa');
            });
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropColumn('certificado_energetico_archivo');
        });
    }
};
