<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        // Buscamos al conductor por su email
        $conductor = User::where('email', 'juan@movilidad.com')->first();

        if ($conductor) {
            Vehiculo::create([
                'placa' => 'SAB-123',
                'modelo' => 'Sprinter 2024',
                'marca' => 'Mercedes-Benz',
                'capacidad' => 18,
                'estado' => 'activo',
                'conductor_id' => $conductor->id
            ]);

            Vehiculo::create([
                'placa' => 'MVC-789',
                'modelo' => 'Coaster',
                'marca' => 'Toyota',
                'capacidad' => 24,
                'estado' => 'activo',
                'conductor_id' => null // Vehículo disponible sin conductor asignado
            ]);
        }
    }
}