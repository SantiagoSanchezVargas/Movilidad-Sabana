<?php

namespace App\Http\Controllers;

use App\Models\Parada;
use App\Models\ParadaConfirmacion;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Auth;

class ConductorParadaController extends Controller
{
    /**
     * Confirmar llegada a una parada
     */
    public function confirmarLlegada(Request $request, Parada $parada)
    {
        $conductor = Auth::user()->conductor;
        $rutaId = $request->input('ruta_id');

        // Validar que la ruta pertenece al conductor
        $ruta = Ruta::find($rutaId);
        if (!$ruta || $ruta->conductor_id !== $conductor->id) {
            return redirect()->back()->with('error', 'No tienes acceso a esta ruta');
        }

        // Verificar si ya existe confirmación
        $confirmacion = ParadaConfirmacion::where('parada_id', $parada->id)
            ->where('conductor_id', $conductor->id)
            ->where('ruta_id', $rutaId)
            ->first();

        if (!$confirmacion) {
            // Crear nueva confirmación
            $confirmacion = ParadaConfirmacion::create([
                'parada_id' => $parada->id,
                'conductor_id' => $conductor->id,
                'ruta_id' => $rutaId,
                'confirmado_en' => now(),
                'latitud_confirmacion' => $parada->latitud, // En producción, usar GPS real
                'longitud_confirmacion' => $parada->longitud,
                'estado' => 'confirmado',
            ]);
        } else {
            // Actualizar si ya existe
            $confirmacion->update([
                'confirmado_en' => now(),
                'estado' => 'confirmado',
            ]);
        }

        // Log de actividad
        \Log::info("Conductor {$conductor->nombre} confirmó llegada a parada {$parada->nombre} en ruta {$ruta->nombre}");

        return redirect()->back()->with('success', "✅ Llegada a '{$parada->nombre}' confirmada a las " . now()->format('H:i:s'));
    }

    /**
     * Registrar pasajeros que subieron
     */
    public function registrarPasajeros(Request $request, Parada $parada)
    {
        $validated = $request->validate([
            'pasajeros' => 'required|integer|min:0|max:100',
            'ruta_id' => 'required|uuid',
        ]);

        $conductor = Auth::user()->conductor;
        $rutaId = $validated['ruta_id'];

        $confirmacion = ParadaConfirmacion::where('parada_id', $parada->id)
            ->where('conductor_id', $conductor->id)
            ->where('ruta_id', $rutaId)
            ->first();

        if ($confirmacion) {
            $confirmacion->update([
                'pasajeros_subieron' => $validated['pasajeros'],
            ]);
        }

        return redirect()->back()->with('success', "Registrados {$validated['pasajeros']} pasajeros");
    }

    /**
     * Ver historial de paradas confirmadas
     */
    public function historial(Request $request)
    {
        $conductor = Auth::user()->conductor;
        
        $confirmaciones = ParadaConfirmacion::where('conductor_id', $conductor->id)
            ->with(['parada', 'ruta'])
            ->orderBy('confirmado_en', 'desc')
            ->paginate(20);

        return view('conductor.paradas.historial', compact('confirmaciones'));
    }
}