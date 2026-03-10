<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email'  => $request->input('email'),
            'password' => $request->input('password'),
        ];

        // Intentar autenticar con guard admin
        $usuario = \App\Models\UsuarioAdmin::where('email', $credentials['email'])
            ->where('activo', 1)
            ->first();

        if ($usuario && Hash::check($credentials['password'], $usuario->password_hash)) {
            // Generar/Actualizar api_token al iniciar sesión web para que el frontend lo use
            $token = Str::random(80);
            $usuario->update(['api_token' => $token]);
            
            Auth::guard('admin')->login($usuario);
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas o cuenta inactiva.'])
            ->withInput($request->only('email'));
    }

    public function showRegister()
    {
        $roles = \App\Models\Rol::all();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombres'   => 'required|string|max:80',
            'apellidos' => 'required|string|max:80',
            'email'     => 'required|email|unique:usuarios_admin,email',
            'password'  => 'required|min:5',
            'id_rol'    => 'required|exists:roles,id_rol',
        ], [
            'email.unique'    => 'El email ya está registrado.',
            'password.min'    => 'La contraseña debe tener al menos 5 caracteres.',
            'id_rol.required' => 'Seleccione un rol.',
        ]);

        \App\Models\UsuarioAdmin::create([
            'nombres'       => $request->nombres,
            'apellidos'     => $request->apellidos,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'id_rol'        => $request->id_rol,
            'activo'        => 1,
        ]);

        return redirect('/login')->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
