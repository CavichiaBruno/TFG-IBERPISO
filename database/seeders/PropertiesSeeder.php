<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertiesSeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::whereIn('role', ['admin', 'agent'])->pluck('id')->toArray();

        $cities = [
            ['city' => 'Madrid',    'province' => 'Madrid'],
            ['city' => 'Barcelona', 'province' => 'Barcelona'],
            ['city' => 'Valencia',  'province' => 'Valencia'],
            ['city' => 'Sevilla',   'province' => 'Sevilla'],
            ['city' => 'Zaragoza',  'province' => 'Zaragoza'],
            ['city' => 'Málaga',    'province' => 'Málaga'],
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

        for ($i = 0; $i < 30; $i++) {
            $location   = $cities[array_rand($cities)];
            $type       = $types[array_rand($types)];
            $operation  = $operations[array_rand($operations)];
            $agentId    = $agents[array_rand($agents)];
            $title      = $titles[array_rand($titles)] . ' - ' . $location['city'];

            Property::create([
                'user_id'            => $agentId,
                'title'              => $title,
                'description'        => 'Increíble propiedad ubicada en una zona privilegiada de ' . $location['city'] . '. Esta propiedad cuenta con acabados de alta calidad, amplias estancias luminosas y una distribución perfecta. Ideal para familias que buscan confort y calidad de vida. La zona dispone de excelentes servicios: colegios, supermercados, transporte público y zonas verdes a pocos metros. No pierdas la oportunidad de conocer esta joya inmobiliaria.',
                'price'              => rand(80000, 850000),
                'surface_m2'         => rand(40, 350),
                'rooms'              => rand(1, 6),
                'bathrooms'          => rand(1, 4),
                'floor'              => rand(0, 10),
                'property_type'      => $type,
                'operation_type'     => $operation,
                'address'            => 'Calle ' . ['Mayor', 'Gran Vía', 'Paseo de la Castellana', 'Av. Diagonal', 'Calle Serrano'][rand(0,4)] . ' ' . rand(1, 200),
                'city'               => $location['city'],
                'province'           => $location['province'],
                'postal_code'        => str_pad(rand(10000, 52000), 5, '0', STR_PAD_LEFT),
                'latitude'           => round(rand(3600, 4380) / 100, 7),
                'longitude'          => round(rand(-730, 330) / 100, 7),
                'has_elevator'       => (bool) rand(0, 1),
                'has_parking'        => (bool) rand(0, 1),
                'has_terrace'        => (bool) rand(0, 1),
                'has_garden'         => (bool) rand(0, 1),
                'has_pool'           => (bool) rand(0, 1),
                'air_conditioning'   => (bool) rand(0, 1),
                'is_featured'        => $i < 6,
                'is_active'          => true,
                'energy_certificate' => $certs[array_rand($certs)],
            ]);
        }
    }
}
