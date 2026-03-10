<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UsuarioAdmin;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado'
            ], 401);
        }

        $usuario = UsuarioAdmin::where('api_token', $token)
            ->where('activo', 1)
            ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ], 401);
        }

        // Podríamos inyectar el usuario en el request si fuera necesario
        // $request->merge(['api_user' => $usuario]);

        return $next($request);
    }
}
