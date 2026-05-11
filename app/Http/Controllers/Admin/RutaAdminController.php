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
            'descripcion' => 'nullable|max:1000',
        ]);

        Ruta::create([
            'id' => (string) Str::uuid(),
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()
            ->route('admin.rutas.index')
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
            'descripcion' => 'nullable|max:1000',
        ]);

        $ruta->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()
            ->route('admin.rutas.index')
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
}