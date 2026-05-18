<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = \App\Models\Role::all();

        return view('auth.register', compact('roles'));
    }
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
            ],

            // UUID del rol
            'role_id' => ['required', 'exists:roles,id'],
        ]);

       $user = User::create([
    'name' => $request->name,
    'apellido' => $request->apellido,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role_id' => $request->role_id,
]);

// ⬇️ AGREGAR ESTO ⬇️
// Si es conductor, crear registro en tabla conductores
if ($user->hasRole('conductor')) {
    \App\Models\Conductor::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'nombre' => $request->name . ' ' . $request->apellido,
        'licencia' => $request->get('licencia', 'N/A'),
        'telefono' => $request->get('telefono', 'N/A'),
        'estado' => 'activo',
    ]);
}
// ⬆️ FIN AGREGAR ⬆️

event(new Registered($user));

        Auth::login($user);

        // Redirección por rol
        if ($user->hasRole('administrador')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('conductor')) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('dashboard');
    }
}