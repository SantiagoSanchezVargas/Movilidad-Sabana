<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro de nuevos usuarios (Estudiantes/Pasajeros)
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'documento' => 'required|string|unique:users',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'documento' => $request->documento,
            'estado' => 'activo',
        ]);

        // Por defecto, se registra como 'pasajero'
        $pasajeroRole = Role::where('nombre', 'pasajero')->first();
        if ($pasajeroRole) {
            $user->roles()->attach($pasajeroRole->id);
        }

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user->load('roles')
        ], 201);
    }

    /**
     * Inicio de sesión
     */
   public function login(Request $request)
{
    // Solo validamos que vengan los datos
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 1. Intentar la autenticación (compara el email y el password con la BD)
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    // 2. Obtener el usuario que acaba de entrar
    $user = Auth::user();

    // 3. Generar la "llave"
    $token = $user->createToken('auth_token')->plainTextToken;

    // 4. Responder con el token
    return response()->json([
        'message' => 'Login exitoso',
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}
    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}