<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $rol = \App\Models\Role::create(['nombre' => 'pasajero']);

        $user = \App\Models\User::create([
            'nombre' => 'Santiago',
            'apellido' => 'Sánchez',
            'email' => 'santiagoasanchez@ucundinamarca.edu.co',
            'password' => Hash::make('password123'),
            'documento' => '1010840935',
            'estado' => 'activo',
        ]);
        
        $user->roles()->attach($rol->id);
    }
}