<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'apellido' => ['required', 'string', 'max:255'], 
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string', 'in:usuario,conductor,administrador'],
    ]);

    // Buscamos el ID del rol en la tabla 'roles'
    $role = \App\Models\Role::where('nombre', $request->role)->first();

    if (!$role) {
        throw ValidationException::withMessages(['role' => 'El rol seleccionado no es válido.']);
    }

    $user = User::create([
        'name' => $request->name,
        'apellido' => $request->apellido,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $role->id, // <--- Guardamos el ID vinculado
    ]);

    event(new Registered($user));

    Auth::login($user);

    // Redirección basada en la relación
    if ($user->hasRole('administrador')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('conductor')) {
        return redirect()->route('conductor.dashboard');
    }

    return redirect()->route('dashboard');
}
}
