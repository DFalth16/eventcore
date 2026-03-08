<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('admin')->check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión para continuar.');
        }
        return $next($request);
    }
}
