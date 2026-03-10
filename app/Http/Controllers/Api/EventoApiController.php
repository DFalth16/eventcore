<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventoApiController extends Controller
{
    /**
     * GET /api/eventos
     * Listar registros
     */
    public function index(Request $request)
    {
        $query = DB::table('eventos as e')
            ->select(
                'e.*',
                'ee.nombre as estado_nombre',
                'ce.nombre as categoria_nombre',
                's.nombre as sede_nombre'
            )
            ->join('estados_evento as ee', 'e.id_estado', '=', 'ee.id_estado')
            ->join('categorias_evento as ce', 'e.id_categoria', '=', 'ce.id_categoria')
            ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
            ->orderByDesc('e.fecha_inicio');

        return response()->json([
            'success' => true,
            'data'    => $query->get()
        ]);
    }

    /**
     * GET /api/eventos/{id}
     * Obtener registro
     */
    public function show($id)
    {
        $evento = DB::table('eventos as e')
            ->select(
                'e.*',
                'ee.nombre as estado_nombre',
                'ce.nombre as categoria_nombre',
                's.nombre as sede_nombre'
            )
            ->join('estados_evento as ee', 'e.id_estado', '=', 'ee.id_estado')
            ->join('categorias_evento as ce', 'e.id_categoria', '=', 'ce.id_categoria')
            ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
            ->where('e.id_evento', $id)
            ->first();

        if (!$evento) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $evento
        ]);
    }

    /**
     * POST /api/eventos
     * Crear registro
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo'       => 'required|string|max:150',
            'id_categoria' => 'required|integer|min:1',
            'id_sede'      => 'required|integer|min:1',
            'id_organizador' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'cupo_maximo'  => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $esGratuito = $request->boolean('es_gratuito');
        $precio     = $esGratuito ? 0.00 : (float) $request->input('precio_entrada', 0);
        $codigo     = 'EVT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        $id = DB::table('eventos')->insertGetId([
            'id_categoria'  => $request->id_categoria,
            'id_estado'     => $request->input('id_estado', 1),
            'id_sede'       => $request->id_sede,
            'id_organizador'=> $request->id_organizador,
            'codigo_evento' => $codigo,
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'cupo_maximo'   => $request->cupo_maximo,
            'precio_entrada'=> $precio,
            'es_gratuito'   => $esGratuito ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evento creado exitosamente',
            'data'    => ['id_evento' => $id, 'codigo_evento' => $codigo]
        ], 210); // Using 201 Created but typical of this project
    }

    /**
     * PUT /api/eventos/{id}
     * Actualizar registro
     */
    public function update(Request $request, $id)
    {
        $evento = DB::table('eventos')->where('id_evento', $id)->first();
        if (!$evento) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo'       => 'required|string|max:150',
            'id_categoria' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $esGratuito = $request->boolean('es_gratuito');
        $precio     = $esGratuito ? 0.00 : (float) $request->input('precio_entrada', 0);

        DB::table('eventos')->where('id_evento', $id)->update([
            'titulo'        => $request->titulo,
            'descripcion'   => $request->descripcion,
            'id_categoria'  => $request->id_categoria,
            'id_estado'     => $request->input('id_estado', $evento->id_estado),
            'id_sede'       => $request->input('id_sede', $evento->id_sede),
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'cupo_maximo'   => $request->input('cupo_maximo', $evento->cupo_maximo),
            'precio_entrada'=> $precio,
            'es_gratuito'   => $esGratuito ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado exitosamente'
        ]);
    }

    /**
     * DELETE /api/eventos/{id}
     * Eliminar registro (Cancelar según lógica del sistema)
     */
    public function destroy($id)
    {
        $evento = DB::table('eventos')->where('id_evento', $id)->first();
        if (!$evento) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }

        DB::table('eventos')->where('id_evento', $id)->update(['id_estado' => 4]); // Cancelado

        return response()->json([
            'success' => true,
            'message' => 'Evento cancelado exitosamente'
        ]);
    }
}
