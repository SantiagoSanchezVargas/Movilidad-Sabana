<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RutaAdminController extends Controller
{
    /* ==========================================
       LISTADO
    ========================================== */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $rutas = Ruta::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

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
       GUARDAR
    ========================================== */
   public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|max:50|unique:rutas,codigo',
            'nombre' => 'required|max:255',
            'paradas' => 'nullable|json',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $ruta = Ruta::create([
                'id' => (string) Str::uuid(),
                'user_id' => auth()->id(),
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'distancia_km' => $request->distancia_km,
                'duracion_estimada' => $request->duracion_estimada,
                'origen' => $request->origen,
                'destino' => $request->destino,
                'estado' => 'activo',
            ]);

            if ($request->has('paradas')) {
                $paradas = json_decode($request->paradas, true);
                foreach ($paradas as $index => $parada) {
                    
                    // --- CORRECCIÓN DE TIPO PARA EL CHECK CONSTRAINT ---
                    $tipo = $parada['tipo'] ?? 'intermedia';
                    if (!in_array($tipo, ['salida', 'intermedia', 'destino'])) {
                        $tipo = 'intermedia'; 
                    }

                    $ruta->paradas()->create([
                        'nombre' => $parada['nombre'] ?? "Parada " . ($index + 1),
                        'numero_orden' => $index + 1,
                        'lat' => $parada['lat'],
                        'lng' => $parada['lng'],
                        'tarifa_desde_origen' => $parada['tarifa'] ?? 0,
                        'tipo_parada' => $tipo, // <--- CAMBIADO: Antes forzaba 'regular'
                        'ubicacion' => null,    // <--- CAMBIADO: El Mutator en Parada.php hará el resto
                        'descripcion' => $parada['descripcion'] ?? '',
                        'radio_metros' => $parada['radio'] ?? 50,
                        'es_obligatoria' => $parada['obligatoria'] ?? false,
                    ]);
                }
            }

            return redirect()
                ->route('admin.rutas.show', $ruta->id)
                ->with('success', 'Ruta creada correctamente.');
        });
    }

    /* ==========================================
       VER DETALLE
    ========================================== */
    public function show($id)
    {
        $ruta = Ruta::with(['paradas' => function ($q) {
            $q->orderBy('numero_orden');
        }])->findOrFail($id);

        return view('admin.rutas.show', compact('ruta'));
    }

    /* ==========================================
       FORM EDITAR
    ========================================== */
    public function edit($id)
    {
        $ruta = Ruta::with(['paradas' => function ($q) {
            $q->orderBy('numero_orden');
        }])->findOrFail($id);

        return view('admin.rutas.edit', compact('ruta'));
    }

    /* ==========================================
       ACTUALIZAR
    ========================================== */
   public function update(Request $request, $id)
    {
        $ruta = Ruta::findOrFail($id);

        $request->validate([
            'codigo' => 'required|max:50|unique:rutas,codigo,' . $ruta->id . ',id',
            'nombre' => 'required|max:255',
            'paradas' => 'nullable|json',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $ruta) {
            $ruta->update($request->only(['codigo', 'nombre', 'descripcion', 'distancia_km', 'duracion_estimada', 'origen', 'destino']));

            if ($request->has('paradas')) {
                $ruta->paradas()->delete(); 
                $paradas = json_decode($request->paradas, true);
                foreach ($paradas as $index => $parada) {
                    
                    // --- CORRECCIÓN DE TIPO ---
                    $tipo = $parada['tipo'] ?? 'intermedia';
                    if (!in_array($tipo, ['salida', 'intermedia', 'destino'])) {
                        $tipo = 'intermedia';
                    }

                    $ruta->paradas()->create([
                        'nombre' => $parada['nombre'] ?? "Parada " . ($index + 1),
                        'numero_orden' => $index + 1,
                        'lat' => $parada['lat'],
                        'lng' => $parada['lng'],
                        'tarifa_desde_origen' => $parada['tarifa'] ?? 0,
                        'tipo_parada' => $tipo,
                        'ubicacion' => null, 
                        'descripcion' => $parada['descripcion'] ?? '',
                        'radio_metros' => $parada['radio'] ?? 50,
                        'es_obligatoria' => $parada['obligatoria'] ?? false,
                    ]);
                }
            }

            return redirect()
                ->route('admin.rutas.show', $ruta->id)
                ->with('success', 'Ruta actualizada correctamente.');
        });
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
       NUEVOS MÉTODOS (CORRECCIÓN ERROR 500)
    ========================================== */

    /**
     * Muestra la vista de reportes de MovSabana.
     * Soluciona el error: Call to undefined method ...::reporte()
     */
    public function reporte()
    {
        return view('admin.rutas.reporte');
    }

    /**
     * API para obtener posiciones en tiempo real para el mapa del Dashboard.
     */
    public function getPosiciones()
    {
        $posiciones = Ruta::select('id', 'nombre', 'latitud', 'longitud', 'estado')->get();
        return response()->json($posiciones);
    }
}