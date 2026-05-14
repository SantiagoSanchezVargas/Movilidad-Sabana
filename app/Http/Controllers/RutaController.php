<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    public function create()
{
    return view('admin.rutas.create');
}
    /* ==========================================
       LISTAR TODAS
    ========================================== */
    public function index()
    {
        return view('mapa');
    }

    /* ==========================================
       VER RUTA CON PARADAS ORDENADAS
    ========================================== */
    public function show($id)
    {
        $ruta = Ruta::with(['paradas' => function ($q) {
            $q->orderBy('numero_orden');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $ruta
        ]);
    }
/* ==========================================
    GUARDAR NUEVA RUTA
========================================== */
public function store(Request $request)
{
    // 1. Validación (Aquí es donde se lanza el error "The codigo has already been taken")
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|unique:rutas,codigo|max:50',
        'precio' => 'nullable|numeric',
        'tiempo_estimado' => 'nullable|integer',
    ]);

    try {
        DB::beginTransaction();

        // 2. Crear la Ruta
        $ruta = Ruta::create([
            'nombre' => $validated['nombre'],
            'codigo' => trim($validated['codigo']), // Limpiamos espacios
            'precio' => $request->precio ?? 5000,
            'tiempo_estimado' => $request->tiempo_estimado ?? 30,
        ]);

        // 3. Si vienen paradas en el request, las guardamos
        // Esto depende de cómo estés enviando el JSON de paradas desde el mapa
        if ($request->has('paradas')) {
            $paradas = json_decode($request->paradas, true);
            foreach ($paradas as $index => $parada) {
                $ruta->paradas()->create([
                    'nombre' => $parada['nombre'] ?? "Parada " . ($index + 1),
                    'lat' => $parada['lat'],
                    'lng' => $parada['lng'],
                    'numero_orden' => $index + 1,
                    // El Mutator en el modelo Parada se encarga de 'ubicacion'
                ]);
            }
        }

        DB::commit();

        return redirect()->route('admin.rutas.index')
                         ->with('success', 'Ruta creada exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
    }
}
    /* ==========================================
       BUSCAR RUTAS CERCANAS
    ========================================== */
public function buscarCercanas(Request $request)
{
    $lat = (float)$request->lat;
    $lng = (float)$request->lng;
    $radio = (float)$request->radio_km;

    $rutas = DB::select("
    SELECT 
        r.id,
        r.nombre,
        r.codigo,

        -- Distancia mínima
        ROUND(
            (
                MIN(
                    ST_DistanceSphere(
                        ST_MakePoint(?, ?),
                        p.ubicacion
                    )
                ) / 1000
            )::numeric,
            2
        ) AS distancia_km,

        -- ORIGEN (numero_orden = 1)
        MIN(p.lat) FILTER (WHERE p.numero_orden = 1) as origen_lat,
        MIN(p.lng) FILTER (WHERE p.numero_orden = 1) as origen_lng,

        -- DESTINO (última parada)
        MIN(p.lat) FILTER (
            WHERE p.numero_orden = (
                SELECT MAX(numero_orden)
                FROM paradas
                WHERE ruta_id = r.id
            )
        ) as destino_lat,

        MIN(p.lng) FILTER (
            WHERE p.numero_orden = (
                SELECT MAX(numero_orden)
                FROM paradas
                WHERE ruta_id = r.id
            )
        ) as destino_lng,

        -- PARADAS COMPLETAS
        json_agg(
            json_build_object(
                'lat', p.lat,
                'lng', p.lng,
                'nombre', p.nombre,
                'orden', p.numero_orden
            )
        ) as paradas

    FROM rutas r
    INNER JOIN paradas p ON p.ruta_id = r.id

    WHERE ST_DWithin(
        p.ubicacion::geography,
        ST_MakePoint(?, ?)::geography,
        ? * 1000
    )

    GROUP BY r.id, r.nombre, r.codigo

    ORDER BY distancia_km ASC
    LIMIT 10
", [$lng, $lat, $lng, $lat, $radio]);
    // =============================
    // 🧠 RANKING INTELIGENTE
    // =============================
    foreach ($rutas as $r) {

        $dist = (float)$r->distancia_km;

        // Puedes cambiar estos valores luego desde DB
        $precio = $r->precio ?? 5000;
        $tiempo = $r->tiempo_estimado ?? 30;

        // Score (MENOR = MEJOR)
        $r->score = ($dist * 0.5) + ($precio / 10000 * 0.3) + ($tiempo / 60 * 0.2);
    }

    // Ordenar por score
    usort($rutas, fn($a, $b) => $a->score <=> $b->score);

    // Marcar mejor opción
    if(count($rutas)){
        $rutas[0]->mejor_opcion = true;
    }

    return response()->json([
        'success' => true,
        'count' => count($rutas),
        'data' => $rutas
    ]);
}
}