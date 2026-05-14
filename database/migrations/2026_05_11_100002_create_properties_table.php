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
                CREATE TABLE propiedades (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    usuario_id BIGINT NOT NULL,
                    titulo VARCHAR(255) NOT NULL,
                    descripcion TEXT NULL,
                    precio NUMERIC(12, 2) NOT NULL,
                    superficie_m2 NUMERIC(10, 2) NULL,
                    habitaciones INTEGER NULL,
                    banos INTEGER NULL,
                    piso VARCHAR(50) NULL,
                    tipo_propiedad VARCHAR(50) NOT NULL,
                    tipo_operacion VARCHAR(50) NOT NULL,
                    direccion VARCHAR(255) NULL,
                    ciudad VARCHAR(100) NULL,
                    provincia VARCHAR(100) NULL,
                    codigo_postal VARCHAR(10) NULL,
                    latitud NUMERIC(10, 7) NULL,
                    longitud NUMERIC(10, 7) NULL,
                    tiene_ascensor BOOLEAN DEFAULT FALSE,
                    tiene_parking BOOLEAN DEFAULT FALSE,
                    tiene_terraza BOOLEAN DEFAULT FALSE,
                    tiene_jardin BOOLEAN DEFAULT FALSE,
                    tiene_piscina BOOLEAN DEFAULT FALSE,
                    aire_acondicionado BOOLEAN DEFAULT FALSE,
                    destacada BOOLEAN DEFAULT FALSE,
                    activa BOOLEAN DEFAULT TRUE,
                    certificado_energetico VARCHAR(50) NULL,
                    url_tour_virtual VARCHAR(500) NULL,
                    slug VARCHAR(255) NULL,
                    deleted_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                )
            ");
        } else {
            DB::statement("
                CREATE TABLE propiedades (
                    id BIGSERIAL PRIMARY KEY,
                    usuario_id BIGINT NOT NULL,
                    titulo VARCHAR(255) NOT NULL,
                    descripcion TEXT NULL,
                    precio NUMERIC(12, 2) NOT NULL,
                    superficie_m2 NUMERIC(10, 2) NULL,
                    habitaciones INTEGER NULL,
                    banos INTEGER NULL,
                    piso VARCHAR(50) NULL,
                    tipo_propiedad VARCHAR(50) NOT NULL,
                    tipo_operacion VARCHAR(50) NOT NULL,
                    direccion VARCHAR(255) NULL,
                    ciudad VARCHAR(100) NULL,
                    provincia VARCHAR(100) NULL,
                    codigo_postal VARCHAR(10) NULL,
                    latitud NUMERIC(10, 7) NULL,
                    longitud NUMERIC(10, 7) NULL,
                    tiene_ascensor BOOLEAN DEFAULT FALSE,
                    tiene_parking BOOLEAN DEFAULT FALSE,
                    tiene_terraza BOOLEAN DEFAULT FALSE,
                    tiene_jardin BOOLEAN DEFAULT FALSE,
                    tiene_piscina BOOLEAN DEFAULT FALSE,
                    aire_acondicionado BOOLEAN DEFAULT FALSE,
                    destacada BOOLEAN DEFAULT FALSE,
                    activa BOOLEAN DEFAULT TRUE,
                    certificado_energetico VARCHAR(50) NULL,
                    url_tour_virtual VARCHAR(500) NULL,
                    slug VARCHAR(255) NULL,
                    deleted_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    CONSTRAINT fk_propiedades_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                )
            ");
        }

        DB::statement("CREATE INDEX propiedades_usuario_id_index ON propiedades(usuario_id)");
        DB::statement("CREATE INDEX propiedades_tipo_propiedad_index ON propiedades(tipo_propiedad)");
        DB::statement("CREATE INDEX propiedades_tipo_operacion_index ON propiedades(tipo_operacion)");
        DB::statement("CREATE INDEX propiedades_ciudad_index ON propiedades(ciudad)");
        DB::statement("CREATE INDEX propiedades_activa_index ON propiedades(activa)");
        DB::statement("CREATE INDEX propiedades_deleted_at_index ON propiedades(deleted_at)");
        DB::statement("CREATE INDEX propiedades_slug_index ON propiedades(slug)");
        DB::statement("CREATE INDEX propiedades_usuario_deleted_index ON propiedades(usuario_id, deleted_at)");
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS propiedades CASCADE");
    }
};



