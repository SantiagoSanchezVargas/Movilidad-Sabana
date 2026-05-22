<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Punto de entrada único para el Dashboard de MovSabana.
     */
    /**
     * Punto de entrada único - redirige según el rol
     */
public function index()
{
    $user = Auth::user();

    // Si es admin, mostrar dashboard admin
  if ($user->hasRole('administrador')) {

    $totalRutas = \App\Models\Ruta::count();
    $rutasActivas = \App\Models\Ruta::where('estado', 'activo')->count();
    $totalConductores = \App\Models\Conductor::count();
    $totalUsuarios = \App\Models\User::count();

    $adminCount = 1;
    $conductorCount = $totalUsuarios > 1 ? intval($totalUsuarios / 2) : 0;
    $pasajeroCount = $totalUsuarios - $adminCount - $conductorCount;

    $rutas = \App\Models\Ruta::with('conductor')->latest()->limit(10)->get();

    // ESTA LÍNEA FALTABA
    $incidentes = \App\Models\Incidente::where('activo', true)->get();

    return view('admin.dashboard', compact(
        'totalRutas',
        'rutasActivas',
        'totalConductores',
        'totalUsuarios',
        'adminCount',
        'conductorCount',
        'pasajeroCount',
        'rutas',
        'incidentes' // Y ESTO TAMBIÉN
    ));
}

// Si es conductor, mostrar dashboard conductor
if ($user->hasRole('conductor')) {
   $conductor = $user->conductor;

$misRutas = $conductor
    ? \App\Models\Ruta::with('paradas')
        ->where('conductor_id', $conductor->id)
        ->get()
    : collect();
    $totalViajes = $misRutas->count();
    $viajesCompletados = $misRutas->where('estado', 'activo')->count();
    $ratingPromedio = 4.8; // Simulado
    $kmRecorridos = $misRutas->sum('distancia_km');
    $ingresosMes = '1200,1500,1800,2000,2200,2500'; // Simulado

    return view('dashboard', compact(
        'totalViajes',
        'viajesCompletados',
        'ratingPromedio',
        'kmRecorridos',
        'ingresosMes',
        'misRutas'
    ));
}
    // Si es pasajero, mostrar dashboard pasajero
$totalViajes = 28; // Simulado
$gastoTotal = 450; // Simulado
$favoritos = 5; // Simulado
$ratingPromedio = 4.8; // Simulado

return view('dashboard', compact(
    'totalViajes',
    'gastoTotal',
    'favoritos',
    'ratingPromedio'
));
}

    /**
     * Vista de Administrador: Mapa completo, gestión de rutas e incidentes.
     */
    private function adminDashboard()
    {
        $data = [
            'totalRutas' => Ruta::count(),
            'rutasActivas' => Ruta::where('estado', 'activo')->count(),
            'rutas' => Ruta::with('conductor')->latest()->get(),
            'incidentes' => Incidente::where('activo', true)->latest()->get(),
            'stats' => [
                'eficiencia' => 92,
                'alertas_hoy' => Incidente::whereDate('created_at', today())->count(),
                'conductores_en_ruta' => Ruta::where('estado', 'activo')->count(),
            ]
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Vista de Conductor: Enfocada en su ruta actual y alertas GPS.
     */
   private function conductorDashboard($user)
{
    $conductor = $user->conductor;

    if (!$conductor) {
        return view('conductores.dashboard', [
            'ruta' => null,
            'misRutas' => collect(),
            'alertasGps' => collect(),
            'estadisticas' => [
                'viajes_totales' => 0,
                'rating_promedio' => 0,
            ]
        ]);
    }

    // Ruta activa
    $rutaAsignada = Ruta::where('conductor_id', $conductor->id)
                        ->where('estado', 'activo')
                        ->first();

    // Todas las rutas
    $misRutas = Ruta::where('conductor_id', $conductor->id)
                    ->latest()
                    ->limit(5)
                    ->get();

    $data = [
        'ruta' => $rutaAsignada,

        'misRutas' => $misRutas,

        'alertasGps' => Incidente::where('activo', true)
                            ->where('tipo', 'desvio')
                            ->latest()
                            ->limit(10)
                            ->get(),

        'estadisticas' => [
            'viajes_totales' => $misRutas->count(),
            'rating_promedio' => $user->rating_promedio ?? 4.5,
        ]
    ];

    return view('conductores.dashboard', $data);
}
    /**
     * Vista de Usuario: Localización de buses y paraderos cercanos.
     */
    private function usuarioDashboard()
{
    return view('dashboard', [
        // Cambiamos 'rutasDisponibles' por 'rutas' para que la vista la reconozca
        'rutas' => Ruta::where('estado', 'activo')->get() 
    ]);
}
}