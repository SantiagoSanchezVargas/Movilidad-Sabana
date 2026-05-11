<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RutaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpieza total
        DB::table('paradas')->delete();
        DB::statement('DELETE FROM rutas CASCADE');

        $admin = User::where('nombre', 'Santiago')->first();

        // --- RUTA 1: CP 1 Portal Norte - Chía ---
        $rutaCP1_id = (string) Str::uuid();
        DB::table('rutas')->insert([
            'id' => $rutaCP1_id,
            'user_id' => $admin->id,
            'nombre' => 'CP 1 Portal Norte - Chía',
            'codigo' => 'CP1-TV',
            'operador' => 'Transvalvanera',
            'color' => '#e11d48',
            'estado' => 'activa',
            'distancia_km' => 18.5, // Campo obligatorio según el error
            'duracion_estimada' => 45, // Agregado por seguridad
            'origen' => DB::raw("ST_GeomFromText('POINT(-74.0456 4.7554)', 4326)"),
            'destino' => DB::raw("ST_GeomFromText('POINT(-74.0577 4.8631)', 4326)"),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $paradasCP1 = [
            'Terminal Portal Norte', 'Centro Comercial 184', 'Mirandela', 
            'Terminal Satélite del Norte', 'Escuela de Ingenieros', 'Multiparque', 
            'Taller 5', 'Porto Aguapanelas Internacionales', 'Colegios UNICOC', 
            'Olímpica de la autopista', 'Entrada Univ. de La Sabana', 'Entrada 1 Centro Chía', 
            'Plaza Mayor', 'Jumbo', 'Gimnasio Smart Fit', 'Arepas y Patacos', 
            'Concha Acústica', 'Río Frío', 'Cementerio', '13-13', 
            'Paradero Emserchía', 'Terminal Chía'
        ];

        foreach ($paradasCP1 as $index => $nombre) {
            $this->insertarParadaMinima($rutaCP1_id, $nombre, $index + 1);
        }

        // --- RUTA 2: Terminal Chía ↔ Bojacá ---
        $rutaBojaca_id = (string) Str::uuid();
        DB::table('rutas')->insert([
            'id' => $rutaBojaca_id,
            'user_id' => $admin->id,
            'nombre' => 'Terminal Chía ↔ Bojacá',
            'codigo' => 'BOJ-001',
            'operador' => 'Sotrans',
            'color' => '#10b981',
            'estado' => 'activa',
            'distancia_km' => 6.2,
            'duracion_estimada' => 20,
            'origen' => DB::raw("ST_GeomFromText('POINT(-74.0577 4.8631)', 4326)"),
            'destino' => DB::raw("ST_GeomFromText('POINT(-74.0722 4.8455)', 4326)"),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        foreach (['Terminal Chía', 'Sector Pradilla', 'Cruce Bojacá', 'Vereda Bojacá'] as $index => $nombre) {
            $this->insertarParadaMinima($rutaBojaca_id, $nombre, $index + 1);
        }

        // --- RUTA 3: Terminal Chía ↔ Variante La Caro ---
        $rutaCaro_id = (string) Str::uuid();
        DB::table('rutas')->insert([
            'id' => $rutaCaro_id,
            'user_id' => $admin->id,
            'nombre' => 'Terminal Chía ↔ Variante La Caro',
            'codigo' => 'VAR-LC',
            'operador' => 'Transvalvanera',
            'color' => '#f59e0b',
            'estado' => 'activa',
            'distancia_km' => 5.8,
            'duracion_estimada' => 15,
            'origen' => DB::raw("ST_GeomFromText('POINT(-74.0577 4.8631)', 4326)"),
            'destino' => DB::raw("ST_GeomFromText('POINT(-74.0321 4.8615)', 4326)"),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        foreach (['Terminal Chía', 'Mc Donalds Chía', 'Univ. La Sabana', 'Variante La Caro'] as $index => $nombre) {
            $this->insertarParadaMinima($rutaCaro_id, $nombre, $index + 1);
        }

        $this->command->info('¡Seeding completado! 3 rutas y todas sus paradas listas.');
    }

    private function insertarParadaMinima($ruta_id, $nombre, $orden)
    {
        DB::table('paradas')->insert([
            'id' => (string) Str::uuid(),
            'ruta_id' => $ruta_id,
            'nombre' => $nombre,
            'numero_orden' => $orden,
            'lat' => 4.8612,
            'lng' => -74.0515,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}