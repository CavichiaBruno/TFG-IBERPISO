<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $types = ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina'];
        $operations = ['venta', 'alquiler'];
        $cities = [
            ['ciudad' => 'Madrid', 'provincia' => 'Madrid'],
            ['ciudad' => 'Barcelona', 'provincia' => 'Barcelona'],
            ['ciudad' => 'Valencia', 'provincia' => 'Valencia'],
            ['ciudad' => 'Sevilla', 'provincia' => 'Sevilla'],
            ['ciudad' => 'Zaragoza', 'provincia' => 'Zaragoza'],
        ];

        $title = fake()->words(3, true);
        $location = $cities[array_rand($cities)];
        
        return [
            'usuario_id' => User::factory(),
            'titulo' => $title,
            'slug' => Str::slug($title),
            'descripcion' => fake()->paragraph(5),
            'tipo_propiedad' => $types[array_rand($types)],
            'tipo_operacion' => $operations[array_rand($operations)],
            'precio' => fake()->randomFloat(2, 50000, 1000000),
            'superficie_m2' => fake()->randomFloat(2, 50, 500),
            'habitaciones' => fake()->numberBetween(1, 6),
            'banos' => fake()->numberBetween(1, 4),
            'direccion' => fake()->address(),
            'ciudad' => $location['ciudad'],
            'provincia' => $location['provincia'],
            'codigo_postal' => fake()->postcode(),
            'activa' => true,
            'destacada' => false,
            'tiene_ascensor' => fake()->boolean(),
            'tiene_parking' => fake()->boolean(),
            'tiene_terraza' => fake()->boolean(),
            'tiene_jardin' => fake()->boolean(),
            'tiene_piscina' => fake()->boolean(),
            'aire_acondicionado' => fake()->boolean(),
        ];
    }
}
