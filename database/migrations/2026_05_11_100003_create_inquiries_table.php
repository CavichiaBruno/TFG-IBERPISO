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
                CREATE TABLE consultas (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    propiedad_id BIGINT NOT NULL,
                    usuario_id BIGINT NULL,
                    nombre_visitante VARCHAR(255) NULL,
                    correo_visitante VARCHAR(255) NULL,
                    telefono_visitante VARCHAR(20) NULL,
                    mensaje TEXT NULL,
                    estado VARCHAR(50) DEFAULT 'pendiente',
                    leida BOOLEAN DEFAULT FALSE,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (propiedad_id) REFERENCES propiedades(id),
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                )
            ");
        } else {
            DB::statement("
                CREATE TABLE consultas (
                    id BIGSERIAL PRIMARY KEY,
                    propiedad_id BIGINT NOT NULL,
                    usuario_id BIGINT NULL,
                    nombre_visitante VARCHAR(255) NULL,
                    correo_visitante VARCHAR(255) NULL,
                    telefono_visitante VARCHAR(20) NULL,
                    mensaje TEXT NULL,
                    estado VARCHAR(50) DEFAULT 'pendiente',
                    leida BOOLEAN DEFAULT FALSE,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    CONSTRAINT fk_consultas_propiedad FOREIGN KEY (propiedad_id) REFERENCES propiedades(id),
                    CONSTRAINT fk_consultas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                )
            ");
        }

        DB::statement("CREATE INDEX consultas_propiedad_id_index ON consultas(propiedad_id)");
        DB::statement("CREATE INDEX consultas_usuario_id_index ON consultas(usuario_id)");
        DB::statement("CREATE INDEX consultas_estado_index ON consultas(estado)");
        DB::statement("CREATE INDEX consultas_leida_index ON consultas(leida)");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS consultas CASCADE");
    }
};
