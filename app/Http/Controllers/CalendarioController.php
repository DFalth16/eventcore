<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Support\Facades\DB;

class CalendarioController extends Controller
{
    public function index()
    {
        $categorias = DB::table('categorias_evento')->orderBy('nombre')->get();
        $sedes      = DB::table('sedes')->orderBy('nombre')->get();
        return view('calendario.index', compact('categorias', 'sedes'));
    }

    public function eventos()
    {
        $eventos = Evento::with(['estado', 'sede'])->get();

        $data = $eventos->map(function ($ev) {
            // Colores según estado
            $color = '#2e4d62'; // Default (Borrador/Finalizado)
            if ($ev->id_estado == 2) $color = '#a3e635'; // Activo (Lime)
            if ($ev->id_estado == 4) $color = '#ff4d6d'; // Cancelado (Rose)

            return [
                'id' => $ev->id_evento,
                'title' => $ev->titulo,
                'start' => $ev->fecha_inicio,
                'end' => $ev->fecha_fin,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'sede' => $ev->sede->nombre ?? 'N/A',
                    'estado' => $ev->estado->nombre ?? 'N/A',
                    'descripcion' => $ev->descripcion,
                    'cupo' => $ev->cupo_maximo,
                    'inscritos' => $ev->inscripciones()->count(),
                ]
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:150',
            'id_categoria' => 'required|integer|min:1',
            'id_sede'      => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'cupo_maximo'  => 'required|integer|min:1',
        ]);

        // Nota: El validador after:fecha_inicio a veces falla con formatos datetime locales de JS
        // Hacemos una validación manual si es necesario o usamos after_or_equal si es el mismo minuto
        if (strtotime($request->fecha_fin) <= strtotime($request->fecha_inicio)) {
            return back()->with('error', 'La fecha de fin debe ser posterior a la de inicio.');
        }

        $codigo = 'EVT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        DB::table('eventos')->insert([
            'id_categoria'  => $request->id_categoria,
            'id_estado'     => 1, // Borrador por defecto
            'id_sede'       => $request->id_sede,
            'id_organizador'=> auth('admin')->id(),
            'codigo_evento' => $codigo,
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'cupo_maximo'   => $request->cupo_maximo,
            'precio_entrada'=> 0.00,
            'es_gratuito'   => 1,
        ]);

        return redirect('/calendario')->with('success', "Evento «{$request->titulo}» creado desde el calendario.");
    }
}
