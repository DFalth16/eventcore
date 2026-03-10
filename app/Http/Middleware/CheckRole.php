<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->guard('admin')->check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión para continuar.');
        }

        $user = auth()->guard('admin')->user();
        
        if (!in_array($user->id_rol, $roles)) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
