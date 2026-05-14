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
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            DB::statement("
                CREATE TABLE usuarios (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nombre VARCHAR(100) NOT NULL,
                    correo VARCHAR(255) NOT NULL UNIQUE,
                    correo_verificado_en TIMESTAMP NULL,
                    contrasena VARCHAR(255) NOT NULL,
                    rol VARCHAR(50) NOT NULL DEFAULT 'usuario',
                    telefono VARCHAR(20) NULL,
                    avatar VARCHAR(255) NULL,
                    activo BOOLEAN NOT NULL DEFAULT TRUE,
                    remember_token VARCHAR(100) NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");

            DB::statement("
                CREATE TABLE password_reset_tokens (
                    email VARCHAR(255) PRIMARY KEY,
                    token VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP NULL
                )
            ");
        } else {
            DB::statement("
                CREATE TABLE usuarios (
                    id BIGSERIAL PRIMARY KEY,
                    nombre VARCHAR(100) NOT NULL,
                    correo VARCHAR(255) NOT NULL UNIQUE,
                    correo_verificado_en TIMESTAMP NULL,
                    contrasena VARCHAR(255) NOT NULL,
                    rol VARCHAR(50) NOT NULL DEFAULT 'usuario',
                    telefono VARCHAR(20) NULL,
                    avatar VARCHAR(255) NULL,
                    activo BOOLEAN NOT NULL DEFAULT TRUE,
                    remember_token VARCHAR(100) NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");

            DB::statement("
                CREATE TABLE password_reset_tokens (
                    email VARCHAR(255) PRIMARY KEY,
                    token VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP NULL
                )
            ");
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS password_reset_tokens CASCADE");
        DB::statement("DROP TABLE IF EXISTS usuarios CASCADE");
    }
};
