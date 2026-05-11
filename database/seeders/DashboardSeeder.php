<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos el ID del rol conductor
        $conductorRolId = DB::table('roles')->where('nombre', 'conductor')->value('id');

        // 2. Aseguramos el conductor en la base de datos
        $conductor = User::firstOrCreate(
            ['email' => 'conductor@movsabana.com'],
            [
                'id' => Str::uuid(),
                'nombre' => 'Pedro',
                'apellido' => 'Guía',
                'password' => bcrypt('password'),
                'role_id' => $conductorRolId,
                'estado' => 'activo', 
            ]
        );

        // 3. Insertamos la ruta con los campos EXACTOS de tu migración
        DB::table('rutas')->insert([
            [
                'id' => Str::uuid(),
                'user_id' => $conductor->id,
                'nombre' => 'Ruta Sabana Centro Directo',
                'codigo' => 'RS-' . strtoupper(Str::random(3)) . '-' . rand(100, 999),
                'distancia_km' => 15.50,
                'duracion_estimada' => 45,
                'estado' => 'activa', // <--- ESTA ES LA PALABRA MÁGICA SEGÚN TU MIGRACIÓN
                'origen' => DB::raw("ST_GeomFromText('POINT(-74.0583 4.8614)', 4326)"), 
                'destino' => DB::raw("ST_GeomFromText('POINT(-74.0451 4.8322)', 4326)"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}