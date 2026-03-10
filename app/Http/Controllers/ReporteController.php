<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->input('year', date('Y'));
        $month = $request->input('month', '');

        // 1. Eventos por categoría (Dona)
        $queryCat = DB::table('eventos')
            ->join('categorias_evento', 'eventos.id_categoria', '=', 'categorias_evento.id_categoria')
            ->select('categorias_evento.nombre', DB::raw('count(*) as total'))
            ->groupBy('categorias_evento.nombre');
        
        if ($year) $queryCat->whereYear('fecha_inicio', $year);
        if ($month) $queryCat->whereMonth('fecha_inicio', $month);
        
        $porCategoria = $queryCat->get();

        // 2. Inscripciones por mes (del año seleccionado)
        $queryMes = DB::table('inscripciones')
            ->select(DB::raw("DATE_FORMAT(fecha_inscripcion, '%Y-%m') as mes"), DB::raw('count(*) as total'))
            ->groupBy('mes')
            ->orderBy('mes');
        
        if ($year) $queryMes->whereYear('fecha_inscripcion', $year);
        
        $inscripcionesMes = $queryMes->get();

        // 3. Top 10 ocupación
        $queryOcup = DB::table('eventos as e')
            ->select('e.titulo', 
                DB::raw('(SELECT COUNT(*) FROM inscripciones WHERE id_evento = e.id_evento) as inscritos'),
                'e.cupo_maximo'
            )
            ->where('e.cupo_maximo', '>', 0);
        
        if ($year) $queryOcup->whereYear('e.fecha_inicio', $year);
        
        $ocupacion = $queryOcup->get()
            ->map(function($ev) {
                $ev->porcentaje = round(($ev->inscritos / $ev->cupo_maximo) * 100, 2);
                return $ev;
            })
            ->sortByDesc('porcentaje')
            ->take(10);

        // 4. Eventos por Sede (Reemplaza Ingresos)
        $querySede = DB::table('eventos as e')
            ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
            ->select('s.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('s.nombre')
            ->orderByDesc('total');
        
        if ($year) $querySede->whereYear('e.fecha_inicio', $year);

        $sedesData = $querySede->get();

        return view('reportes.index', compact('porCategoria', 'inscripcionesMes', 'ocupacion', 'sedesData', 'year', 'month'));
    }

    public function show(Request $request, $tipo)
    {
        $year  = $request->input('year', date('Y'));
        $data = null;
        $label = '';

        switch ($tipo) {
            case 'categorias':
                $data = DB::table('eventos')
                    ->join('categorias_evento', 'eventos.id_categoria', '=', 'categorias_evento.id_categoria')
                    ->select('categorias_evento.nombre', DB::raw('count(*) as total'))
                    ->whereYear('fecha_inicio', $year)
                    ->groupBy('categorias_evento.nombre')
                    ->get();
                $label = 'Eventos por Categoría';
                break;
            case 'inscripciones':
                $data = DB::table('inscripciones')
                    ->select(DB::raw("DATE_FORMAT(fecha_inscripcion, '%Y-%m') as mes"), DB::raw('count(*) as total'))
                    ->whereYear('fecha_inscripcion', $year)
                    ->groupBy('mes')
                    ->orderBy('mes')
                    ->get();
                $label = 'Inscripciones por Mes';
                break;
            case 'ocupacion':
                $data = DB::table('eventos as e')
                    ->select('e.titulo', 
                        DB::raw('(SELECT COUNT(*) FROM inscripciones WHERE id_evento = e.id_evento) as inscritos'),
                        'e.cupo_maximo'
                    )
                    ->where('e.cupo_maximo', '>', 0)
                    ->whereYear('e.fecha_inicio', $year)
                    ->get()
                    ->map(function($ev) {
                        $ev->porcentaje = round(($ev->inscritos / $ev->cupo_maximo) * 100, 2);
                        return $ev;
                    })
                    ->sortByDesc('porcentaje')
                    ->values();
                $label = 'Ocupación por Evento';
                break;
            case 'sedes':
                $data = DB::table('eventos as e')
                    ->join('sedes as s', 'e.id_sede', '=', 's.id_sede')
                    ->select('s.nombre', DB::raw('COUNT(*) as total'))
                    ->whereYear('e.fecha_inicio', $year)
                    ->groupBy('s.nombre')
                    ->orderByDesc('total')
                    ->get();
                $label = 'Eventos por Sede';
                break;
        }

        return view('reportes.show', compact('data', 'tipo', 'label', 'year'));
    }
}
