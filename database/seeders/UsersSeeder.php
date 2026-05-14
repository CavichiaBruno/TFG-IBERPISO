<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['correo' => 'admin@iberpiso.es'], [
            'nombre'     => 'Administrador IberPiso',
            'contrasena' => Hash::make('password'),
            'rol'     => 'admin',
            'telefono'    => '+34 600 000 001',
            'activo'=> true,
        ]);

        
        $users = [
            ['nombre' => 'Juan Martínez',   'correo' => 'juan@example.com'],
            ['nombre' => 'Ana Rodríguez',   'correo' => 'ana@example.com'],
            ['nombre' => 'Pedro Sánchez',   'correo' => 'pedro@example.com'],
            ['nombre' => 'Laura Fernández', 'correo' => 'laura@example.com'],
            ['nombre' => 'Miguel Torres',   'correo' => 'miguel@example.com'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['correo' => $u['correo']], [
                'nombre'     => $u['nombre'],
                'contrasena' => Hash::make('password'),
                'rol'     => 'usuario',
                'activo'=> true,
            ]);
        }
    }
}
