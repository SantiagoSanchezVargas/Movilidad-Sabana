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
        $user = auth()->user();

        // Redirige a la vista correspondiente según el rol
        if ($user->hasRole('administrador')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('conductor')) {
            return $this->conductorDashboard($user);
        } else {
            return $this->usuarioDashboard();
        }
    }

    /**
     * Vista de Administrador: Mapa completo, gestión de rutas e incidentes.
     */
    private function adminDashboard()
    {
        $data = [
            'totalRutas' => Ruta::count(),
            'rutasActivas' => Ruta::where('estado', 'activo')->count(),
            'rutas' => Ruta::with(['conductor', 'vehiculo'])->latest()->get(),
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
        // Obtener la ruta asignada hoy (activa)
        $rutaAsignada = Ruta::where('user_id', $user->id)
                            ->where('estado', 'activo')
                            ->first();
        
        $data = [
            'ruta' => $rutaAsignada,
            'misRutas' => Ruta::where('user_id', $user->id)
                              ->latest()
                              ->limit(5)
                              ->get(),
            // Solo incidentes que afecten su zona de operación
            'alertasGps' => Incidente::where('activo', true)
                            ->where('tipo', 'desvio')
                            ->latest()
                            ->limit(10)
                            ->get(),
            'estadisticas' => [
                'viajes_totales' => Ruta::where('user_id', $user->id)->count(),
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