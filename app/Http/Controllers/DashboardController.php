<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEventos       = DB::table('eventos')->count();
        $eventosActivos     = DB::table('eventos')->where('id_estado', 2)->count();
        $eventosCancelados  = DB::table('eventos')->where('id_estado', 4)->count();
        $eventosFinalizados = DB::table('eventos')->where('id_estado', 5)->count();
        $totalAsistentes    = DB::table('participantes')->count();
        $totalSedes         = DB::table('sedes')->count();

        $nuevosHoy = DB::table('inscripciones')
            ->whereDate('fecha_inscripcion', now()->toDateString())
            ->count();

        $stats = [
            'total_eventos'          => $totalEventos,
            'eventos_activos'        => $eventosActivos,
            'total_asistentes'       => $totalAsistentes,
            'total_sedes'            => $totalSedes,
            'eventos_cancelados'     => $eventosCancelados,
            'eventos_finalizados'    => $eventosFinalizados,
            'nuevos_inscritos_hoy'   => $nuevosHoy,
        ];

        $eventos = DB::select("
            SELECT e.titulo AS nombre, e.fecha_inicio AS fecha,
                   (SELECT COUNT(*) FROM inscripciones i WHERE i.id_evento = e.id_evento) AS inscritos,
                   e.cupo_maximo AS cupo,
                   ee.nombre AS estado,
                   ce.nombre AS cat
            FROM eventos e
            JOIN estados_evento ee ON e.id_estado = ee.id_estado
            JOIN categorias_evento ce ON e.id_categoria = ce.id_categoria
            ORDER BY e.fecha_inicio DESC
            LIMIT 5
        ");

        $actividad = DB::select("
            SELECT
                CASE
                    WHEN ei.nombre = 'Cancelada' THEN 'Cancelación'
                    ELSE 'Nueva inscripción'
                END AS accion,
                CONCAT(pa.nombres, ' ', pa.apellidos, ' → ', ev.titulo) AS det,
                i.fecha_inscripcion AS fecha,
                CASE
                    WHEN ei.nombre = 'Cancelada' THEN 'can'
                    ELSE 'ins'
                END AS tipo
            FROM inscripciones i
            JOIN participantes pa ON i.id_participante = pa.id_participante
            JOIN eventos ev ON i.id_evento = ev.id_evento
            JOIN estados_inscripcion ei ON i.id_estado_inscripcion = ei.id_estado_inscripcion
            ORDER BY i.fecha_inscripcion DESC
            LIMIT 6
        ");

        return view('dashboard', compact('stats', 'eventos', 'actividad'));
    }
}
