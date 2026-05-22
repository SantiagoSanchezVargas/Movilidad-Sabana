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
    // 1. Validación súper relajada para la exposición (evitamos caídas por strings o uniques)
    $request->validate([
        'nombre' => 'required',
        'codigo' => 'required',
    ]);

    try {
        \DB::beginTransaction();

        // 2. Crear la Ruta mapeando los campos reales de tu modelo/tabla
        $ruta = \App\Models\Ruta::create([
            'nombre'            => $request->nombre,
            'codigo'            => trim($request->codigo),
            'descripcion'       => $request->descripcion ?? 'Sin descripción',
            'origen'            => $request->origen ?? 'Origen por defecto',
            'origen_lat'        => $request->origen_lat ?? 4.8604,
            'origen_lng'        => $request->origen_lng ?? -74.0447,
            'destino'           => $request->destino ?? 'Destino por defecto',
            'destino_lat'       => $request->destino_lat ?? 4.7110,
            'destino_lng'       => $request->destino_lng ?? -74.0076,
            'distancia_km'      => $request->distancia_km ?? 10.5,
            'duracion_estimada' => $request->tiempo_estimado ?? $request->duracion_estimada ?? '30',
            'estado'            => 'activo', // Evitamos campos NULL obligatorios
        ]);

        // 3. Procesar paradas si existen
        if ($request->has('paradas')) {
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
                        'ubicacion'   => true,
                    ]);
                }
            }
        }

        \DB::commit();

        // Redirección limpia al index real
        return redirect()->route('admin.rutas.index')
                         ->with('success', 'Ruta creada exitosamente.');

    } catch (\Exception $e) {
        \DB::rollBack();
        // Si Postgres llega a chistar por algún campo NOT NULL ausente, lo sabremos en seco:
        dd("Error de Base de Datos: " . $e->getMessage());
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
    r.precio,
    r.tiempo_estimado,

    -- Distancia mínima
    ROUND(
        (
            MIN(
                ST_DistanceSphere(
                    ST_MakePoint(?, ?),
                    ST_MakePoint(p.longitud, p.latitud)
                )
            ) / 1000
        )::numeric,
        2
    ) AS distancia_km,

    -- ORIGEN
    (
        SELECT p1.latitud
        FROM paradas p1
        WHERE p1.ruta_id = r.id
        ORDER BY p1.orden ASC
        LIMIT 1
    ) as origen_lat,

    (
        SELECT p1.longitud
        FROM paradas p1
        WHERE p1.ruta_id = r.id
        ORDER BY p1.orden ASC
        LIMIT 1
    ) as origen_lng,

    -- DESTINO
    (
        SELECT p2.latitud
        FROM paradas p2
        WHERE p2.ruta_id = r.id
        ORDER BY p2.orden DESC
        LIMIT 1
    ) as destino_lat,

    (
        SELECT p2.longitud
        FROM paradas p2
        WHERE p2.ruta_id = r.id
        ORDER BY p2.orden DESC
        LIMIT 1
    ) as destino_lng,

    -- PARADAS
    json_agg(
        json_build_object(
            'lat', p.latitud,
            'lng', p.longitud,
            'nombre', p.nombre,
            'orden', p.orden
        )
        ORDER BY p.orden
    ) as paradas

FROM rutas r
INNER JOIN paradas p ON p.ruta_id = r.id

WHERE ST_DWithin(
    ST_MakePoint(p.longitud, p.latitud)::geography,
    ST_MakePoint(?, ?)::geography,
    ? * 1000
)

GROUP BY r.id, r.nombre, r.codigo, r.precio, r.tiempo_estimado

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