<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipanteController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $query  = DB::table('participantes');

        if ($search) {
            $like = "%{$search}%";
            $query->where(function ($q) use ($like) {
                $q->where('nombres', 'LIKE', $like)
                  ->orWhere('apellidos', 'LIKE', $like)
                  ->orWhere('email', 'LIKE', $like)
                  ->orWhere('documento_id', 'LIKE', $like);
            });
        }

        $participantes = $query->orderBy('apellidos')->orderBy('nombres')->get();
        return view('participantes.index', compact('participantes', 'search'));
    }

    public function create()
    {
        return view('participantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres'   => 'required|string|max:80',
            'apellidos' => 'required|string|max:80',
            'email'     => 'required|email|unique:participantes,email',
        ], [
            'email.unique' => 'Ya existe un participante con ese email.',
        ]);

        DB::table('participantes')->insert([
            'nombres'      => $request->nombres,
            'apellidos'    => $request->apellidos,
            'email'        => $request->email,
            'telefono'     => $request->telefono,
            'documento_id' => $request->documento_id,
            'creado_en'    => now(),
        ]);

        return redirect('/participantes')
            ->with('success', "Participante «{$request->nombres} {$request->apellidos}» registrado correctamente.");
    }

    public function edit($id)
    {
        $participante = DB::table('participantes')->where('id_participante', $id)->first();
        if (!$participante) {
            return redirect('/participantes')->with('error', 'Participante no encontrado.');
        }
        return view('participantes.edit', compact('participante'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres'   => 'required|string|max:80',
            'apellidos' => 'required|string|max:80',
            'email'     => "required|email|unique:participantes,email,{$id},id_participante",
        ], [
            'email.unique' => 'El email ya pertenece a otro participante.',
        ]);

        DB::table('participantes')->where('id_participante', $id)->update([
            'nombres'      => $request->nombres,
            'apellidos'    => $request->apellidos,
            'email'        => $request->email,
            'telefono'     => $request->telefono,
            'documento_id' => $request->documento_id,
        ]);

        return redirect('/participantes')->with('success', 'Participante actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            DB::table('participantes')->where('id_participante', $id)->delete();
            return redirect('/participantes')->with('success', 'Participante eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect('/participantes')
                ->with('error', 'No se puede eliminar: el participante tiene inscripciones asociadas.');
        }
    }
}
