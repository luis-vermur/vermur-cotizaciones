<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            // Pricing
            ['name' => 'Ivan Maldonado',  'email' => 'ivan.maldonado@vermur.com',    'password' => 'Ivan2025!',    'rol' => 'pricing'],
            ['name' => 'Cecilia Medrano', 'email' => 'cecilia.medrano@vermur.com', 'password' => 'Cecilia2025!', 'rol' => 'pricing'],
            ['name' => 'Carlos Gone',     'email' => 'carlos.gone@vermur.com',  'password' => 'Carlos2025!',  'rol' => 'pricing'],
            ['name' => 'Nohema Sosa',     'email' => 'nohema.sosa@vermur.com',  'password' => 'Nohema2025!',  'rol' => 'pricing'],
            // Ventas
            ['name' => 'Itzel Laurean',   'email' => 'itzel.laurean@vermur.com',   'password' => 'Itzel2025!',   'rol' => 'ventas'],
            ['name' => 'Noemi Casas',     'email' => 'noemi.casas@vermur.com',   'password' => 'Noemi2025!',   'rol' => 'ventas'],
            ['name' => 'Yuri Morales',    'email' => 'yuri.morales@vermur.com',    'password' => 'Yuri2025!',    'rol' => 'ventas'],
            // Admin
            ['name' => 'Administrador',   'email' => 'admin@vermur.com',   'password' => 'Admin2025!',   'rol' => 'admin'],
        ];

        foreach ($usuarios as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'password' => Hash::make($u['password']),
                    'rol'      => $u['rol'],
                    'activo'   => true,
                ]
            );
        }
    }
}