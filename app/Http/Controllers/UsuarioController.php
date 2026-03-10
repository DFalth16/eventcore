<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('usuarios_admin as u')
            ->select('u.*', 'r.nombre_rol as rol')
            ->join('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->orderByDesc('u.creado_en')
            ->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = DB::table('roles')->orderBy('id_rol')->get();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres'  => 'required|string|max:80',
            'email'    => 'required|email|unique:usuarios_admin,email',
            'password' => 'required|min:5',
            'id_rol'   => 'required|integer|min:1',
        ], [
            'email.unique'    => 'Este email ya está registrado.',
            'password.min'    => 'La contraseña debe tener al menos 5 caracteres.',
            'id_rol.required' => 'Seleccione un rol válido.',
        ]);

        DB::table('usuarios_admin')->insert([
            'id_rol'        => $request->id_rol,
            'nombres'       => $request->nombres,
            'apellidos'     => $request->apellidos,
            'email'         => $request->email,
            'password_hash' => password_hash($request->password, PASSWORD_DEFAULT),
            'activo'        => 1,
            'creado_en'     => now(),
        ]);

        return redirect('/usuarios')->with('success', "Usuario {$request->nombres} creado correctamente.");
    }

    public function edit($id)
    {
        $usuario = DB::table('usuarios_admin')->where('id_usuario', $id)->first();
        if (!$usuario) {
            return redirect('/usuarios')->with('error', 'Usuario no encontrado.');
        }
        $roles = DB::table('roles')->orderBy('id_rol')->get();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string|max:80',
            'email'   => 'required|email',
        ]);

        $data = [
            'nombres'   => $request->nombres,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'id_rol'    => $request->id_rol,
            'activo'    => $request->has('activo') ? 1 : 0,
        ];

        if (!empty($request->password)) {
            $data['password_hash'] = password_hash($request->password, PASSWORD_DEFAULT);
        }

        DB::table('usuarios_admin')->where('id_usuario', $id)->update($data);

        return redirect('/usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $currentAdminId = auth('admin')->id();

        // Evitar que el usuario se elimine a sí mismo
        if ($currentAdminId == $id) {
            return redirect('/usuarios')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        try {
            DB::transaction(function () use ($id, $currentAdminId) {
                // Reasignar eventos al administrador actual para evitar error de integridad
                DB::table('eventos')
                    ->where('id_organizador', $id)
                    ->update(['id_organizador' => $currentAdminId]);

                // Eliminar el usuario
                DB::table('usuarios_admin')->where('id_usuario', $id)->delete();
            });

            return redirect('/usuarios')->with('success', 'Usuario eliminado y sus eventos han sido reasignados a tu cuenta.');
        } catch (\Exception $e) {
            return redirect('/usuarios')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $usuario = DB::table('usuarios_admin')->where('id_usuario', $id)->first();
        if (!$usuario) return redirect('/usuarios')->with('error', 'Usuario no encontrado.');

        $nuevoEstado = $usuario->activo ? 0 : 1;
        DB::table('usuarios_admin')->where('id_usuario', $id)->update(['activo' => $nuevoEstado]);

        $msg = $nuevoEstado ? 'Usuario activado.' : 'Usuario desactivado.';
        return redirect('/usuarios')->with('success', $msg);
    }
}
