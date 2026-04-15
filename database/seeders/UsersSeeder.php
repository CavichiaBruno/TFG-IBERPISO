<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@iberpiso.es'], [
            'name'     => 'Administrador IberPiso',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '+34 600 000 001',
            'is_active'=> true,
        ]);

        User::updateOrCreate(['email' => 'agente1@iberpiso.es'], [
            'name'     => 'Carlos García',
            'password' => Hash::make('password'),
            'role'     => 'agent',
            'phone'    => '+34 600 000 002',
            'is_active'=> true,
        ]);

        User::updateOrCreate(['email' => 'agente2@iberpiso.es'], [
            'name'     => 'María López',
            'password' => Hash::make('password'),
            'role'     => 'agent',
            'phone'    => '+34 600 000 003',
            'is_active'=> true,
        ]);

        $users = [
            ['name' => 'Juan Martínez',   'email' => 'juan@example.com'],
            ['name' => 'Ana Rodríguez',   'email' => 'ana@example.com'],
            ['name' => 'Pedro Sánchez',   'email' => 'pedro@example.com'],
            ['name' => 'Laura Fernández', 'email' => 'laura@example.com'],
            ['name' => 'Miguel Torres',   'email' => 'miguel@example.com'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], [
                'name'     => $u['name'],
                'password' => Hash::make('password'),
                'role'     => 'user',
                'is_active'=> true,
            ]);
        }
    }
}
