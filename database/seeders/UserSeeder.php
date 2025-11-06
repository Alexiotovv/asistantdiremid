<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@diremid.com',
            'password' => Hash::make('admindire#8973'),
            'role' => 'admin',
            'dni' => '00000001',
            'nombre_completo' => 'Administrador del Sistema',
            'oficina' => 'Administración',
            'clave_pin' => '0000',
        ]);

        // Opcional: Crear usuarios de ejemplo para pruebas
        User::create([
            'name' => 'Empleado 1',
            'email' => 'empleado1@diremid.com',
            'password' => Hash::make('admindire#24123'),
            'role' => 'personal',
            'dni' => '12345678',
            'nombre_completo' => 'Juan Pérez',
            'oficina' => 'Ventas',
            'clave_pin' => '1234',
        ]);

        User::create([
            'name' => 'Empleado 2',
            'email' => 'empleado2@example.com',
            'password' => Hash::make('admindire#24123'),
            'role' => 'personal',
            'dni' => '87654321',
            'nombre_completo' => 'María López',
            'oficina' => 'Contabilidad',
            'clave_pin' => '5678',
        ]);

        // Agregue aquí más usuarios según necesite
    }
}