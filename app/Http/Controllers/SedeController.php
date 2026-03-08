<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = DB::table('sedes')->orderBy('nombre')->get();
        return view('sedes.index', compact('sedes'));
    }

    public function create()
    {
        return view('sedes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:120',
            'direccion' => 'required|string|max:200',
            'ciudad'    => 'required|string|max:80',
            'capacidad' => 'required|integer|min:1',
        ]);

        DB::table('sedes')->insert([
            'nombre'    => $request->nombre,
            'direccion' => $request->direccion,
            'ciudad'    => $request->ciudad,
            'pais'      => $request->input('pais', 'Bolivia'),
            'capacidad' => $request->capacidad,
            'referencia'=> $request->referencia,
        ]);

        return redirect('/sedes')->with('success', "Sede «{$request->nombre}» creada correctamente.");
    }

    public function edit($id)
    {
        $sede = DB::table('sedes')->where('id_sede', $id)->first();
        if (!$sede) {
            return redirect('/sedes')->with('error', 'Sede no encontrada.');
        }
        return view('sedes.edit', compact('sede'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'    => 'required|string|max:120',
            'direccion' => 'required|string|max:200',
            'ciudad'    => 'required|string|max:80',
        ]);

        DB::table('sedes')->where('id_sede', $id)->update([
            'nombre'    => $request->nombre,
            'direccion' => $request->direccion,
            'ciudad'    => $request->ciudad,
            'pais'      => $request->input('pais', 'Bolivia'),
            'capacidad' => $request->capacidad,
            'referencia'=> $request->referencia,
        ]);

        return redirect('/sedes')->with('success', 'Sede actualizada correctamente.');
    }

    public function destroy($id)
    {
        try {
            DB::table('sedes')->where('id_sede', $id)->delete();
            return redirect('/sedes')->with('success', 'Sede eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect('/sedes')->with('error', 'No se puede eliminar la sede porque tiene eventos asociados.');
        }
    }
}
