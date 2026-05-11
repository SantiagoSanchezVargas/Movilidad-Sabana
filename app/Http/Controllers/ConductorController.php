<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string',
        'licencia' => 'required|string|unique:conductores',
        'telefono' => 'required|string',
    ]);

    $conductor = Conductor::create([
        'id' => (string) str()->uuid(),
        ...$validated
    ]);

    return response()->json($conductor, 201);
}

public function disponibles()
{
    return Conductor::where('estado', 'disponible')->get();
}
}
