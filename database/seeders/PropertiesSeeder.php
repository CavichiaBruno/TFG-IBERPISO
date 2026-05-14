<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertiesSeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::where('rol', 'admin')->pluck('id')->toArray();

        $cities = [
            ['ciudad' => 'Madrid',    'provincia' => 'Madrid'],
            ['ciudad' => 'Barcelona', 'provincia' => 'Barcelona'],
            ['ciudad' => 'Valencia',  'provincia' => 'Valencia'],
            ['ciudad' => 'Sevilla',   'provincia' => 'Sevilla'],
            ['ciudad' => 'Zaragoza',  'provincia' => 'Zaragoza'],
            ['ciudad' => 'Málaga',    'provincia' => 'Málaga'],
        ];

        $types      = ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina'];
        $operations = ['venta', 'alquiler'];
        $certs      = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        $titles = [
            'Acogedor piso en el centro histórico',
            'Luminoso apartamento con vistas panorámicas',
            'Amplia casa familiar con jardín privado',
            'Moderno chalet con piscina y terraza',
            'Exclusivo ático de lujo con terrazas',
            'Piso reformado en zona prime',
            'Casa adosada con garaje doble',
            'Oficina en edificio corporativo',
            'Local comercial en avenida principal',
            'Plaza de garaje en parking seguro',
        ];

        for ($i = 0; $i < 50; $i++) {
            $location   = $cities[array_rand($cities)];
            $type       = $types[array_rand($types)];
            $operation  = $operations[array_rand($operations)];
            $agentId    = $agents[array_rand($agents)];
            $title      = $titles[$i % count($titles)] . ' - ' . $location['ciudad'];

            Property::create([
                'usuario_id'             => $agentId,
                'titulo'                 => $title,
                'descripcion'            => 'Increíble propiedad ubicada en una zona privilegiada de ' . $location['ciudad'] . '. Esta propiedad cuenta con acabados de alta calidad, amplias estancias luminosas y una distribución perfecta. Ideal para familias que buscan confort y calidad de vida. La zona dispone de excelentes servicios: colegios, supermercados, transporte público y zonas verdes a pocos metros. No pierdas la oportunidad de conocer esta joya inmobiliaria.',
                'precio'                 => rand(250000, 2850000), // Precios altos para una sensación premium
                'superficie_m2'          => rand(120, 550),
                'habitaciones'           => rand(3, 8),
                'banos'                  => rand(2, 6),
                'piso'                   => rand(0, 5),
                'tipo_propiedad'         => $i < 5 ? 'chalet' : ($i < 7 ? 'piso' : 'casa'),
                'tipo_operacion'         => $operation,
                'direccion'              => 'Calle ' . ['Mayor', 'Gran Vía', 'Paseo de la Castellana', 'Av. Diagonal', 'Calle Serrano'][rand(0,4)] . ' ' . rand(1, 200),
                'ciudad'                 => $location['ciudad'],
                'provincia'              => $location['provincia'],
                'codigo_postal'          => str_pad(rand(10000, 52000), 5, '0', STR_PAD_LEFT),
                'latitud'                => round(rand(3600, 4380) / 100, 7),
                'longitud'               => round(rand(-730, 330) / 100, 7),
                'tiene_ascensor'         => true,
                'tiene_parking'          => true,
                'tiene_terraza'          => true,
                'tiene_jardin'           => true,
                'tiene_piscina'          => true,
                'aire_acondicionado'     => true,
                'destacada'              => true,
                'activa'                 => true,
                'certificado_energetico' => $certs[array_rand($certs)],
            ]);
        }
    }
}
