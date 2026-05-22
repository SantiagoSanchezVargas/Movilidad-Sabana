<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use Illuminate\Http\Request;
use App\Models\Conductor;
use Illuminate\Support\Str;

class RutaAdminController extends Controller
{
    /* ==========================================
       LISTADO (Inyección automática si está vacío)
    ========================================== */
/* ==========================================
       LISTADO (Solución de user_id y $buscar)
    ========================================== */
    public function index(Request $request)
    {
        // 🚀 INYECCIÓN REAL PARA LA SUSTENTACIÓN CON USER_ID
        if (\App\Models\Ruta::count() === 0) {
            try {
                \App\Models\Ruta::create([
                    'id'                => (string) \Illuminate\Support\Str::uuid(),
                    'user_id'           => auth()->id() ?? '019e5144-fc83-72c2-a5da-824b7d1ddc87', // Asigna el usuario logueado
                    'nombre'            => 'Chía T - CC Centro Chía',
                    'codigo'            => 'R-PRO1',
                    'descripcion'       => 'Ruta de prueba de conectividad intermunicipal',
                    'origen'            => 'Terminal Chía',
                    'destino'           => 'CC Centro Chía',
                    'distancia_km'      => 4.8,
                    'duracion_estimada' => '30 min',
                    'estado'            => 'activo',
                    'parametros_ruta'   => [
                        'origen_coordenadas'  => ['lat' => 4.8604, 'lng' => -74.0447],
                        'destino_coordenadas' => ['lat' => 4.7110, 'lng' => -74.0076]
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Fallo la inyección del index: ' . $e->getMessage());
            }
        }

        // Definimos la variable buscar para que la barra de búsqueda de la vista no falle
        $buscar = $request->get('buscar', '');

        // 🎯 Usamos paginate en lugar de all() para solucionar el error de hasPages()
        $rutas = \App\Models\Ruta::paginate(10);

        return view('admin.rutas.index', compact('rutas', 'buscar'));
    }
    /* ==========================================
       FORM CREAR
    ========================================== */
    public function create()
    {
        return view('admin.rutas.create');
    }

   /* ==========================================
       GUARDAR BLINDADO (Sin fallos de variables)
    ========================================== */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'codigo' => 'required',
        ]);

        try {
            // Decodificar el JSON de las paradas creadas en el mapa
            $paradasJson = $request->has('paradas') ? (is_array($request->paradas) ? $request->paradas : json_decode($request->paradas, true)) : [];
            
            // Coordenadas base por defecto (Chía)
            $origenLat = 4.8604;
            $origenLng = -74.0447;
            $destinoLat = 4.7110;
            $destinoLng = -74.0076;

            // Si el admin marcó puntos en el mapa, extraemos origen y destino
            if (is_array($paradasJson) && count($paradasJson) > 0) {
                $origenLat = (float) $paradasJson[0]['lat'];
                $origenLng = (float) $paradasJson[0]['lng'];
                $destinoLat = (float) $paradasJson[count($paradasJson) - 1]['lat'];
                $destinoLng = (float) $paradasJson[count($paradasJson) - 1]['lng'];
            }

            $parametrosRuta = [
                'origen_coordenadas'  => ['lat' => $origenLat, 'lng' => $origenLng],
                'destino_coordenadas' => ['lat' => $destinoLat, 'lng' => $destinoLng]
            ];

            // 💾 Guardamos la ruta principal inyectando todas las columnas posibles
            \App\Models\Ruta::create([
                'id'                => (string) \Illuminate\Support\Str::uuid(),
                'user_id'           => auth()->id() ?? '019e5144-fc83-72c2-a5da-824b7d1ddc87',
                'nombre'            => $request->nombre,
                'codigo'            => trim($request->codigo),
                'descripcion'       => $request->descripcion ?? 'Sin descripción',
                'origen'            => $request->origen ?? 'Terminal Chía',
                'origen_lat'        => $origenLat,
                'origen_lng'        => $origenLng,
                'destino'           => $request->destino ?? 'Centro Chía',
                'destino_lat'       => $destinoLat,
                'destino_lng'       => $destinoLng,
                'distancia_km'      => $request->distancia_km ?? 4.8,
                'duracion_estimada' => $request->duracion_estimada ?? '30 min',
                'estado'            => 'activo',
                'conductor_id'      => null,
                'parametros_ruta'   => $parametrosRuta,
            ]);

            return redirect()->route('admin.rutas.index')
                ->with('success', 'Ruta creada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error crítico: ' . $e->getMessage());
        }
    }
    /* ==========================================
       VER DETALLE
    ========================================== */
    public function show($id)
    {
        $ruta = Ruta::with(['paradas' => function ($q) {
            $q->orderBy('orden'); // Cambiado a 'orden' para coincidir con tu modelo Ruta.php
        }])->findOrFail($id);

        return view('admin.rutas.show', compact('ruta'));
    }

    /* ==========================================
       FORM EDITAR
    ========================================== */
    public function edit($id)
    {
        $ruta = Ruta::findOrFail($id);
        $conductores = Conductor::all();
       
        return view('admin.rutas.edit', compact('ruta', 'conductores'));
    }

    /* ==========================================
       ACTUALIZAR
    ========================================== */
    public function update(Request $request, $id)
    {
        $ruta = Ruta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:255',
            'codigo' => 'required|unique:rutas,codigo,' . $ruta->id . ',id',
        ]);
        
        $ruta->update([
            'nombre'            => $request->nombre,
            'codigo'            => trim($request->codigo),
            'descripcion'       => $request->descripcion ?? $ruta->descripcion,
            'origen'            => $request->origen ?? $ruta->origen,
            'destino'           => $request->destino ?? $ruta->destino,
            'distancia_km'      => $request->distancia_km ?? $ruta->distancia_km,
            'duracion_estimada' => $request->duracion_estimada ?? $ruta->duracion_estimada,
            'estado'            => $request->estado ?? $ruta->estado,
            'conductor_id'      => $request->conductor_id ?? $ruta->conductor_id,
        ]);

        if ($request->has('paradas')) {
            $ruta->paradas()->delete();
            $paradas = is_array($request->paradas) ? $request->paradas : json_decode($request->paradas, true);
            if (is_array($paradas)) {
                foreach ($paradas as $index => $parada) {
                    $ruta->paradas()->create([
                        'nombre'      => $parada['nombre'] ?? "Parada " . ($index + 1),
                        'latitud'     => $parada['lat'] ?? $parada['latitud'],
                        'longitud'    => $parada['lng'] ?? $parada['longitud'],
                        'orden'       => $index + 1,
                        'tipo'        => $parada['tipo'] ?? 'intermedia',
                        'tarifa'      => $parada['tarifa'] ?? 0,
                        'descripcion' => $parada['descripcion'] ?? '',
                        'radio'       => $parada['radio'] ?? 50,
                        'obligatoria' => $parada['obligatoria'] ?? false,
                    ]);
                }
            }
        }

        return redirect()->route('admin.rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    /* ==========================================
       ELIMINAR
    ========================================== */
    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->delete();

        return redirect()
            ->route('admin.rutas.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }

    /* ==========================================
       MÉTODOS ADICIONALES MovSabana
    ========================================== */
    public function reporte()
    {
        return view('admin.rutas.reporte');
    }

    public function getPosiciones()
    {
        $posiciones = Ruta::select('id', 'nombre', 'estado')->get();
        return response()->json($posiciones);
    }
    
    public function mostrarParadas(Ruta $ruta)
    {
        $paradas = $ruta->paradas()->orderBy('orden')->get(); 
        return view('admin.rutas.paradas.index', compact('ruta', 'paradas'));
    }
 
    public function crearParada(Request $request, Ruta $ruta)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'latitud'  => 'required|numeric',
            'longitud' => 'required|numeric',
            'orden'    => 'required|integer|min:1',
        ]);
     
        $parada = $ruta->paradas()->create($validated);
        return redirect()->back()->with('success', "✅ Parada '{$parada->nombre}' creada exitosamente");
    }
 
    public function actualizarParada(Request $request, $paradaId)
    {
        $parada = \App\Models\Parada::findOrFail($paradaId);
        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'latitud'  => 'required|numeric',
            'longitud' => 'required|numeric',
            'orden'    => 'required|integer|min:1',
        ]);
     
        $parada->update($validated);
        return redirect()->back()->with('success', "✅ Parada actualizada");
    }
 
    public function eliminarParada($paradaId)
    {
        $parada = \App\Models\Parada::findOrFail($paradaId);
        $nombreParada = $parada->nombre;
        $parada->delete();
        return redirect()->back()->with('success', "✅ Parada '{$nombreParada}' eliminada");
    }
}