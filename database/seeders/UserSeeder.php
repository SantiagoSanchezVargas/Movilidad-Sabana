<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos los roles
        $adminRole = Role::where('nombre', 'admin')->first();
        $conductorRole = Role::where('nombre', 'conductor')->first();

        // 2. Creamos tu usuario Admin
        $admin = User::create([
            'nombre' => 'Santiago',
            'apellido' => 'Sánchez',
            'email' => 'santiago@unisabana.edu.co',
            'password' => Hash::make('password123'), // Cambia esto después
            'documento' => '12345678',
            'estado' => 'activo'
        ]);
        $admin->roles()->attach($adminRole->id);

        // 3. Creamos un Conductor de prueba
        $conductor = User::create([
            'nombre' => 'Juan',
            'apellido' => 'Conductor',
            'email' => 'juan@movilidad.com',
            'password' => Hash::make('password123'),
            'documento' => '87654321',
            'estado' => 'activo'
        ]);
        $conductor->roles()->attach($conductorRole->id);
    }
}