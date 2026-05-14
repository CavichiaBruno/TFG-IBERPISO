<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Database\Seeder;

class PropertyMediaSeeder extends Seeder
{
    public function run(): void
    {
        // Usar raw query para evitar problemas de cached plan en PostgreSQL con SoftDeletes
        $properties = \DB::table('propiedades')->whereNull('deleted_at')->get();
        $imageCount = 8; // User provided 8 images now

        foreach ($properties as $index => $property) {
            // Añadimos 3 imágenes por propiedad para que se vea el carrusel
            for ($j = 0; $j < 3; $j++) {
                $imageNumber = (($index + $j) % $imageCount) + 1;
                $imagePath = 'images/properties/prop' . $imageNumber . '.jpg';

                PropertyMedia::create([
                    'propiedad_id'      => $property->id,
                    'ruta_archivo'      => $imagePath,
                    'tipo_archivo'      => 'imagen',
                    'tipo_mime'         => 'image/jpeg',
                    'tamano_archivo_kb' => 500,
                    'nombre_original'   => 'prop' . $imageNumber . '.jpg',
                    'es_portada'        => ($j === 0), // La primera es la de portada
                    'orden'             => $j,
                ]);
            }
        }
    }
}
