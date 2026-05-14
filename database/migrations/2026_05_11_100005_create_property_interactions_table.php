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
                CREATE TABLE interacciones_propiedades (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    usuario_id BIGINT NOT NULL,
                    propiedad_id BIGINT NOT NULL,
                    tipo VARCHAR(50) NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
                    FOREIGN KEY (propiedad_id) REFERENCES propiedades(id)
                )
            ");
        } else {
            DB::statement("
                CREATE TABLE interacciones_propiedades (
                    id BIGSERIAL PRIMARY KEY,
                    usuario_id BIGINT NOT NULL,
                    propiedad_id BIGINT NOT NULL,
                    tipo VARCHAR(50) NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    CONSTRAINT fk_interacciones_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
                    CONSTRAINT fk_interacciones_propiedad FOREIGN KEY (propiedad_id) REFERENCES propiedades(id)
                )
            ");
        }

        DB::statement("CREATE INDEX interacciones_propiedades_usuario_id_index ON interacciones_propiedades(usuario_id)");
        DB::statement("CREATE INDEX interacciones_propiedades_propiedad_id_index ON interacciones_propiedades(propiedad_id)");
        DB::statement("CREATE INDEX interacciones_propiedades_tipo_index ON interacciones_propiedades(tipo)");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS interacciones_propiedades CASCADE");
    }
};
