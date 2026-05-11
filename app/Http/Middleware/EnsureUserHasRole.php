<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario no está logueado o no tiene el rol necesario
        if (!$request->user() || !$request->user()->hasRole($role)) {
            // Lo mandamos al dashboard general con un mensaje de error
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta zona.');
        }

        return $next($request);
    }
}
