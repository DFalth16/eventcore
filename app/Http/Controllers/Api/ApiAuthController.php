<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsuarioAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    /**
     * POST /api/login
     * Autenticación de usuario para obtener Bearer Token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $usuario = UsuarioAdmin::where('email', $request->email)
            ->where('activo', 1)
            ->first();

        // Verificamos contraseña (usamos password_verify ya que en el controlador 
        // original se usa password_hash con PASSWORD_DEFAULT que es compatible con Hash::check)
        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Generamos un nuevo token
        $token = Str::random(80);
        $usuario->update(['api_token' => $token]);

        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'data'    => [
                'user'  => [
                    'nombres'   => $usuario->nombres,
                    'apellidos' => $usuario->apellidos,
                    'email'     => $usuario->email,
                ],
                'token' => $token
            ]
        ]);
    }

    /**
     * POST /api/logout
     * Revocar token
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $usuario = UsuarioAdmin::where('api_token', $token)->first();
            if ($usuario) {
                $usuario->update(['api_token' => null]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Token revocado correctamente'
        ]);
    }
}
