<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'admin',
                'descripcion' => 'Administrador total del sistema',
                'permisos' => json_encode(['all' => true])
            ],
            [
                'nombre' => 'conductor',
                'descripcion' => 'Usuario que presta el servicio de transporte',
                'permisos' => json_encode(['drive' => true, 'report_congestion' => true])
            ],
            [
                'nombre' => 'pasajero',
                'descripcion' => 'Estudiante o administrativo que usa el servicio',
                'permisos' => json_encode(['book_ride' => true, 'rate_driver' => true])
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}