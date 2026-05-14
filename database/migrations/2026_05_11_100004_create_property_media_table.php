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
                CREATE TABLE medios_propiedades (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    propiedad_id BIGINT NOT NULL,
                    ruta_archivo VARCHAR(500) NOT NULL,
                    tipo_archivo VARCHAR(50) NOT NULL,
                    tipo_mime VARCHAR(100) NULL,
                    tamano_archivo_kb BIGINT NULL,
                    nombre_original VARCHAR(255) NULL,
                    es_portada BOOLEAN DEFAULT FALSE,
                    orden INTEGER DEFAULT 0,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (propiedad_id) REFERENCES propiedades(id)
                )
            ");
        } else {
            DB::statement("
                CREATE TABLE medios_propiedades (
                    id BIGSERIAL PRIMARY KEY,
                    propiedad_id BIGINT NOT NULL,
                    ruta_archivo VARCHAR(500) NOT NULL,
                    tipo_archivo VARCHAR(50) NOT NULL,
                    tipo_mime VARCHAR(100) NULL,
                    tamano_archivo_kb BIGINT NULL,
                    nombre_original VARCHAR(255) NULL,
                    es_portada BOOLEAN DEFAULT FALSE,
                    orden INTEGER DEFAULT 0,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    CONSTRAINT fk_medios_propiedad FOREIGN KEY (propiedad_id) REFERENCES propiedades(id)
                )
            ");
        }

        DB::statement("CREATE INDEX medios_propiedades_propiedad_id_index ON medios_propiedades(propiedad_id)");
        DB::statement("CREATE INDEX medios_propiedades_tipo_archivo_index ON medios_propiedades(tipo_archivo)");
        DB::statement("CREATE INDEX medios_propiedades_es_portada_index ON medios_propiedades(es_portada)");
        DB::statement("CREATE INDEX medios_propiedades_orden_index ON medios_propiedades(orden)");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS medios_propiedades CASCADE");
    }
};
