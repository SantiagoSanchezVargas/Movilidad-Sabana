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
    public function index()
{
    $user = auth()->user();
    
    // Obtenemos las rutas según el contexto
    $rutas = match(true) {
        $user->hasRole('administrador') => Ruta::with(['conductor', 'vehiculo'])->get(),
        $user->hasRole('conductor')     => Ruta::where('user_id', $user->id)->get(),
        default                        => Ruta::where('estado', 'activo')->get(),
    };

    return view('dashboard', compact('rutas'));
}

    /**
     * Vista de Administrador: Mapa completo, gestión de rutas e incidentes.
     */
    private function adminDashboard()
    {
        $data = [
            'totalRutas' => Ruta::count(),
            'rutas' => Ruta::all(),
            // Cargamos el incidente de red (Ej: Desvío en Variante Chía)
            'incidentes' => Incidente::where('activo', true)->get(),
            'stats' => [
                'eficiencia' => 92,
                'alertas_hoy' => Incidente::whereDate('created_at', today())->count()
            ]
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Vista de Conductor: Enfocada en su ruta actual y alertas GPS.
     */
    private function conductorDashboard($user)
    {
        // Supongamos que el modelo User tiene una relación 'ruta'
        $rutaAsignada = $user->ruta ?? null; 
        
        $data = [
            'ruta' => $rutaAsignada,
            // Solo incidentes que afecten su zona de operación
            'alertasGps' => Incidente::where('activo', true)
                            ->where('tipo', 'desvio')
                            ->latest()
                            ->get()
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