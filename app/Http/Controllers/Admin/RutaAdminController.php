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
        'nombre' => 'required|max:255',
        'codigo' => 'required|unique:rutas,codigo',
        'origen' => 'required',
        'destino' => 'required',
        'distancia_km' => 'required|numeric',
        'duracion_estimada' => 'required',
        'estado' => 'required|in:activo,inactivo',
        'conductor_id' => 'nullable|exists:conductores,id',
    ]);

    $ruta = Ruta::create([
        'id' => (string) Str::uuid(),
        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'origen' => $request->origen,
        'destino' => $request->destino,
        'distancia_km' => $request->distancia_km,
        'duracion_estimada' => $request->duracion_estimada,
        'estado' => $request->estado,
        'conductor_id' => $request->conductor_id,
    ]);

    // Guardar paradas
    if ($request->has('paradas')) {
        foreach ($request->paradas as $parada) {
            $ruta->paradas()->create($parada);
        }
    }

    return redirect()->route('admin.rutas.index')
        ->with('success', 'Ruta creada correctamente.');
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
        'origen' => 'required',
        'destino' => 'required',
        'distancia_km' => 'required|numeric',
        'duracion_estimada' => 'required',
        'estado' => 'required|in:activo,inactivo',
        'conductor_id' => 'nullable|exists:conductores,id',
    ]);
    

    $ruta->update([
        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'origen' => $request->origen,
        'destino' => $request->destino,
        'distancia_km' => $request->distancia_km,
        'duracion_estimada' => $request->duracion_estimada,
        'estado' => $request->estado,
        'conductor_id' => $request->conductor_id,
    ]);

    // Actualizar paradas si existen
    if ($request->has('paradas')) {
        $ruta->paradas()->delete();
        foreach ($request->paradas as $parada) {
            $ruta->paradas()->create($parada);
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
    /**
 * Mostrar paradas de una ruta
 */
public function mostrarParadas(Ruta $ruta)
{
    $paradas = $ruta->paradas()->ordenadas()->get();
    return view('admin.rutas.paradas.index', compact('ruta', 'paradas'));
}
 
/**
 * Crear parada en una ruta
 */
public function crearParada(Request $request, Ruta $ruta)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'orden' => 'required|integer|min:1',
        'hora_estimada' => 'nullable|date_format:H:i',
        'descripcion' => 'nullable|string|max:1000',
    ]);
 
    $parada = $ruta->paradas()->create($validated);
    
    return redirect()->back()->with('success', "✅ Parada '{$parada->nombre}' creada exitosamente");
}
 
/**
 * Actualizar parada
 */
public function actualizarParada(Request $request, Parada $parada)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'orden' => 'required|integer|min:1',
        'hora_estimada' => 'nullable|date_format:H:i',
        'descripcion' => 'nullable|string|max:1000',
    ]);
 
    $parada->update($validated);
    
    return redirect()->back()->with('success', "✅ Parada actualizada");
}
 
/**
 * Eliminar parada
 */
public function eliminarParada(Parada $parada)
{
    $nombreParada = $parada->nombre;
    $parada->delete();
    
    return redirect()->back()->with('success', "✅ Parada '{$nombreParada}' eliminada");
}
}