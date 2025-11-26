<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Uso: ->middleware('role:1,2')  → permite admin y asesor
     *      *    ->middleware('role:1')      → solo admin
     *      *    ->middleware('role:4')      → solo ingeniero
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  ...$roles  ← uno o varios tipos permitidos
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Si no está autenticado → login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        // Verifica si el tipo_usuario del usuario está en los permitidos
        if (!in_array($user->tipo_usuario, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Opcional: si el usuario está inactivo
        if (!$user->estatus) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'usuario_nombre' => 'Tu cuenta está inactiva. Contacta al administrador.'
            ]);
        }

        return $next($request);
    }
}