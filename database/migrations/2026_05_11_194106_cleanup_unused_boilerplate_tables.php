<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        // 1. Eliminar tablas redundantes en español que no usa Laravel por defecto
        Schema::dropIfExists('sesiones');
        Schema::dropIfExists('tokens_reinicio_contrasena');

        // 2. Crear password_reset_tokens con el nombre que espera Laravel si no existe
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
