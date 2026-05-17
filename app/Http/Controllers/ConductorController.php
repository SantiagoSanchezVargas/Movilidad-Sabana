<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::paginate(20);
        return view('admin.conductores.index', compact('conductores'));
    }

    public function create()
    {
        return view('admin.conductores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'licencia' => 'required|unique:conductores,licencia',
            'telefono' => 'required|max:20',
        ]);

        Conductor::create([
            'id' => (string) Str::uuid(),
            'nombre' => $request->nombre,
            'licencia' => $request->licencia,
            'telefono' => $request->telefono,
            'estado' => 'Activo',
        ]);

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor creado correctamente.');
    }

    public function edit($id)
    {
        $conductor = Conductor::findOrFail($id);
        return view('admin.conductores.edit', compact('conductor'));
    }

    public function update(Request $request, $id)
    {
        $conductor = Conductor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:255',
            'licencia' => 'required|unique:conductores,licencia,' . $conductor->id . ',id',
            'telefono' => 'required|max:20',
            'estado' => 'required|in:activo,inactivo,suspendido',
        ]);

        $conductor->update([
            'nombre' => $request->nombre,
            'licencia' => $request->licencia,
            'telefono' => $request->telefono,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $conductor = Conductor::findOrFail($id);
        $conductor->delete();

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor eliminado correctamente.');
    }
}