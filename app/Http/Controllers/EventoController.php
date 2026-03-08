<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $search    = trim($request->input('q', ''));
        $catFilter = $request->input('cat', '');
        $estFilter = $request->input('estado', '');

        $query = DB::table('eventos as e')
            ->select(
                'e.*',
                'ee.nombre as estado',
                'ce.nombre as categoria',
                's.nombre as sede',
                'ua.nombres as org_nombres',
                'ua.apellidos as org_apellidos',
                DB::raw('(SELECT COUNT(*) FROM inscripciones i WHERE i.id_evento = e.id_evento) AS inscritos')
            )
            ->join('estados_evento as ee', 'e.id_estado', '=', 'ee.id_estado')
            ->join('categorias_evento as ce', 'e.id_categoria', '=', 'ce.id_categoria')
            ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
            ->join('usuarios_admin as ua', 'e.id_organizador', '=', 'ua.id_usuario')
            ->orderByDesc('e.fecha_inicio');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('e.titulo', 'LIKE', "%{$search}%")
                  ->orWhere('e.codigo_evento', 'LIKE', "%{$search}%")
                  ->orWhere('s.nombre', 'LIKE', "%{$search}%");
            });
        }
        if ($catFilter) {
            $query->where('e.id_categoria', $catFilter);
        }
        if ($estFilter) {
            $query->where('e.id_estado', $estFilter);
        }

        $eventos    = $query->get();
        $categorias = DB::table('categorias_evento')->orderBy('nombre')->get();
        $estados    = DB::table('estados_evento')->orderBy('id_estado')->get();

        return view('eventos.index', compact('eventos', 'categorias', 'estados', 'search', 'catFilter', 'estFilter'));
    }

    public function create()
    {
        $categorias = DB::table('categorias_evento')->orderBy('nombre')->get();
        $estados    = DB::table('estados_evento')->orderBy('id_estado')->get();
        $sedes      = DB::table('sedes')->orderBy('nombre')->get();
        return view('eventos.create', compact('categorias', 'estados', 'sedes'));
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
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',
        ]);

        $esGratuito = $request->boolean('es_gratuito');
        $precio     = $esGratuito ? 0.00 : (float) $request->input('precio_entrada', 0);
        $codigo     = 'EVT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        DB::table('eventos')->insert([
            'id_categoria'  => $request->id_categoria,
            'id_estado'     => $request->input('id_estado', 1),
            'id_sede'       => $request->id_sede,
            'id_organizador'=> auth('admin')->id(),
            'codigo_evento' => $codigo,
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'cupo_maximo'   => $request->cupo_maximo,
            'precio_entrada'=> $precio,
            'es_gratuito'   => $esGratuito ? 1 : 0,
        ]);

        return redirect('/eventos')->with('success', "Evento «{$request->titulo}» creado exitosamente.");
    }

    public function edit($id)
    {
        $evento = DB::table('eventos')->where('id_evento', $id)->first();
        if (!$evento) {
            return redirect('/eventos')->with('error', 'Evento no encontrado.');
        }
        $categorias = DB::table('categorias_evento')->orderBy('nombre')->get();
        $estados    = DB::table('estados_evento')->orderBy('id_estado')->get();
        $sedes      = DB::table('sedes')->orderBy('nombre')->get();
        return view('eventos.edit', compact('evento', 'categorias', 'estados', 'sedes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo'       => 'required|string|max:150',
            'id_categoria' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',
        ]);

        $esGratuito = $request->boolean('es_gratuito');
        $precio     = $esGratuito ? 0.00 : (float) $request->input('precio_entrada', 0);

        DB::table('eventos')->where('id_evento', $id)->update([
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'id_categoria'  => $request->id_categoria,
            'id_estado'     => $request->input('id_estado', 1),
            'id_sede'       => $request->id_sede,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'cupo_maximo'   => $request->cupo_maximo,
            'precio_entrada'=> $precio,
            'es_gratuito'   => $esGratuito ? 1 : 0,
        ]);

        return redirect('/eventos')->with('success', "Evento «{$request->titulo}» actualizado exitosamente.");
    }

    public function destroy($id)
    {
        $evento = DB::table('eventos')->where('id_evento', $id)->first();
        DB::table('eventos')->where('id_evento', $id)->update(['id_estado' => 4]); // Cancelado
        $titulo = $evento->titulo ?? 'Evento';
        return redirect('/eventos')->with('success', "Evento «{$titulo}» cancelado correctamente.");
    }

    // ─── Inscritos (GET) ──────────────────────────────────────────────────────
    public function inscritosMostrar($id)
    {
        $evento = DB::table('eventos as e')
            ->select('e.*', 's.nombre as sede', 'ee.nombre as estado',
                DB::raw('(SELECT COUNT(*) FROM inscripciones WHERE id_evento = e.id_evento) AS total_inscritos'))
            ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
            ->join('estados_evento as ee', 'e.id_estado', '=', 'ee.id_estado')
            ->where('e.id_evento', $id)
            ->first();

        if (!$evento) {
            return redirect('/eventos')->with('error', 'Evento no encontrado.');
        }

        $inscritos = DB::table('inscripciones as i')
            ->select('i.*', 'p.nombres', 'p.apellidos', 'p.email', 'p.telefono',
                'ei.nombre as estado_inscripcion')
            ->join('participantes as p', 'i.id_participante', '=', 'p.id_participante')
            ->join('estados_inscripcion as ei', 'i.id_estado_inscripcion', '=', 'ei.id_estado_inscripcion')
            ->where('i.id_evento', $id)
            ->orderByDesc('i.fecha_inscripcion')
            ->get();

        $errors = [];
        return view('eventos.inscritos', compact('evento', 'inscritos', 'errors'));
    }

    // ─── Inscritos (POST) ─────────────────────────────────────────────────────
    public function inscritosStore(Request $request, $id)
    {
        $evento = DB::table('eventos')->where('id_evento', $id)->first();
        if (!$evento) {
            return redirect('/eventos')->with('error', 'Evento no encontrado.');
        }

        $email  = trim($request->input('email_participante', ''));
        $errors = [];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ingrese un email válido del participante.';
        } else {
            $participante = DB::table('participantes')->where('email', $email)->first();
            if (!$participante) {
                $errors[] = "No existe un participante con el email «{$email}». Primero regístrelo en el módulo de Participantes.";
            } else {
                $total = DB::table('inscripciones')->where('id_evento', $id)->count();
                if ($total >= $evento->cupo_maximo) {
                    $errors[] = 'El evento ha alcanzado su aforo máximo.';
                } else {
                    $yaInscrito = DB::table('inscripciones')
                        ->where('id_evento', $id)
                        ->where('id_participante', $participante->id_participante)
                        ->exists();
                    if ($yaInscrito) {
                        $errors[] = "«{$participante->nombres} {$participante->apellidos}» ya está inscrito en este evento.";
                    }
                }
            }
        }

        if (empty($errors)) {
            $codigo = 'INS-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            DB::table('inscripciones')->insert([
                'id_evento'            => $id,
                'id_participante'      => $participante->id_participante,
                'id_estado_inscripcion'=> 1,
                'codigo_inscripcion'   => $codigo,
                'fecha_inscripcion'    => now(),
            ]);
            return redirect("/eventos/{$id}/inscritos")
                ->with('success', "«{$participante->nombres} {$participante->apellidos}» inscrito. Código: {$codigo}");
        }

        // Recargar vista con errores
        $inscritos = DB::table('inscripciones as i')
            ->select('i.*', 'p.nombres', 'p.apellidos', 'p.email', 'p.telefono',
                'ei.nombre as estado_inscripcion')
            ->join('participantes as p', 'i.id_participante', '=', 'p.id_participante')
            ->join('estados_inscripcion as ei', 'i.id_estado_inscripcion', '=', 'ei.id_estado_inscripcion')
            ->where('i.id_evento', $id)
            ->orderByDesc('i.fecha_inscripcion')
            ->get();

        $eventoFull = DB::table('eventos as e')
            ->select('e.*', 's.nombre as sede', 'ee.nombre as estado',
                DB::raw('(SELECT COUNT(*) FROM inscripciones WHERE id_evento = e.id_evento) AS total_inscritos'))
            ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
            ->join('estados_evento as ee', 'e.id_estado', '=', 'ee.id_estado')
            ->where('e.id_evento', $id)
            ->first();

        return view('eventos.inscritos', [
            'evento'    => $eventoFull,
            'inscritos' => $inscritos,
            'errors'    => $errors,
        ]);
    }
}
