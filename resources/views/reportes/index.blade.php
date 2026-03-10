@extends('layouts.app')

@section('title', 'Reportes')
@section('page_title', 'Reportes y Analíticas')

@section('head_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection

@section('extra_styles')
<style>
    .rep-grid { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        gap: 20px; 
    }
    .chart-container { 
        background: var(--c1); 
        border: 1px solid var(--border); 
        border-radius: var(--r); 
        padding: 20px; 
        position: relative; 
        height: 320px;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s var(--ease), border-color 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }
    .chart-container:hover {
        transform: translateY(-5px);
        border-color: var(--cyan);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .chart-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 15px; 
        flex-shrink: 0;
    }
    .chart-title { font-size: 13px; font-weight: 500; color: var(--t1); letter-spacing: 0.5px; }
    .chart-body { flex-grow: 1; position: relative; min-height: 0; }
    .btn-detalle { font-size: 10px; color: var(--cyan); opacity: 0; transition: opacity 0.3s; }
    .chart-container:hover .btn-detalle { opacity: 1; }
    canvas { width: 100% !important; height: 100% !important; }
</style>
@endsection

@section('content')
<!-- Barra de Filtros -->
<div style="margin-bottom: 24px; background: var(--c1); border: 1px solid var(--border); border-radius: var(--r); padding: 18px; display: flex; gap: 15px; align-items: center">
    <div style="font-size: 13px; color: var(--t2); font-weight: 500">Filtrar Reportes:</div>
    <form action="/reportes" method="GET" style="display: flex; gap: 12px; align-items: center; flex-grow: 1">
        <select name="month" class="form-control" style="width: 140px; margin-bottom: 0">
            <option value="">Todos los Meses</option>
            @php $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
            @foreach($meses as $i => $m)
                <option value="{{ $i+1 }}" {{ $month == ($i+1) ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
        <select name="year" class="form-control" style="width: 100px; margin-bottom: 0">
            @for($y = date('Y')-2; $y <= date('Y')+3; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="btn btn-p" style="padding: 10px 20px">Aplicar Filtros</button>
        <a href="/reportes" class="btn btn-g" style="padding: 10px 15px">Limpiar</a>
    </form>
    <button onclick="exportFullPDF()" class="btn btn-g" style="background: var(--violet-g); color: var(--violet); border-color: rgba(139,92,246,0.2)">
        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF General
    </button>
</div>

<div class="rep-grid" id="allCharts">
    <!-- 1. Eventos por Categoría -->
    <a href="/reportes/categorias?year={{ $year }}" class="chart-container">
        <div class="chart-header">
            <div class="chart-title">Distribución por Categoría</div>
            <span class="btn-detalle">Ver detalle <i class="bi bi-plus-circle"></i></span>
        </div>
        <div class="chart-body">
            @if($porCategoria->isEmpty())
                <div style="display:flex; height:100%; align-items:center; justify-content:center; color:var(--t4); font-size:12px">No hay datos para este periodo</div>
            @endif
            <canvas id="chartCat"></canvas>
        </div>
    </a>

    <!-- 2. Inscripciones por Mes -->
    <a href="/reportes/inscripciones?year={{ $year }}" class="chart-container">
        <div class="chart-header">
            <div class="chart-title">Inscripciones por Periodo</div>
            <span class="btn-detalle">Ver detalle <i class="bi bi-plus-circle"></i></span>
        </div>
        <div class="chart-body">
            @if($inscripcionesMes->isEmpty())
                <div style="display:flex; height:100%; align-items:center; justify-content:center; color:var(--t4); font-size:12px">No hay datos para este periodo</div>
            @endif
            <canvas id="chartMes"></canvas>
        </div>
    </a>

    <!-- 3. Top Ocupación -->
    <a href="/reportes/ocupacion?year={{ $year }}" class="chart-container">
        <div class="chart-header">
            <div class="chart-title">Ocupación por Evento (%)</div>
            <span class="btn-detalle">Ver detalle <i class="bi bi-plus-circle"></i></span>
        </div>
        <div class="chart-body">
            @if($ocupacion->isEmpty())
                <div style="display:flex; height:100%; align-items:center; justify-content:center; color:var(--t4); font-size:12px">No hay datos para este periodo</div>
            @endif
            <canvas id="chartOcupacion"></canvas>
        </div>
    </a>

    <!-- 4. Eventos por Sede -->
    <a href="/reportes/sedes?year={{ $year }}" class="chart-container">
        <div class="chart-header">
            <div class="chart-title">Eventos por Sede</div>
            <span class="btn-detalle">Ver detalle <i class="bi bi-plus-circle"></i></span>
        </div>
        <div class="chart-body">
            @if($sedesData->isEmpty())
                <div style="display:flex; height:100%; align-items:center; justify-content:center; color:var(--t4); font-size:12px">No hay datos para este periodo</div>
            @endif
            <canvas id="chartSedes"></canvas>
        </div>
    </a>
</div>
@endsection

@section('scripts')
<script>
    Chart.defaults.color = '#6e90a8';
    Chart.defaults.font.family = 'Outfit';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';

    const showCharts = {!! $porCategoria->isNotEmpty() || $inscripcionesMes->isNotEmpty() || $ocupacion->isNotEmpty() || $sedesData->isNotEmpty() ? 'true' : 'false' !!};

    if (showCharts) {
        // 1. Categorías
        const ctxCat = document.getElementById('chartCat');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($porCategoria->pluck('nombre')) !!},
                datasets: [{
                    data: {!! json_encode($porCategoria->pluck('total')) !!},
                    backgroundColor: ['#00d4ff', '#a3e635', '#f59e0b', '#8b5cf6', '#ff4d6d', '#0099cc'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });

        // 2. Meses
        const ctxMes = document.getElementById('chartMes');
        new Chart(ctxMes, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inscripcionesMes->pluck('mes')) !!},
                datasets: [{
                    label: 'Inscritos',
                    data: {!! json_encode($inscripcionesMes->pluck('total')) !!},
                    backgroundColor: 'rgba(0, 212, 255, 0.4)',
                    borderColor: '#00d4ff',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 3. Ocupación
        const ctxOcup = document.getElementById('chartOcupacion');
        new Chart(ctxOcup, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ocupacion->pluck('titulo')) !!},
                datasets: [{
                    label: '% Ocupación',
                    data: {!! json_encode($ocupacion->pluck('porcentaje')) !!},
                    backgroundColor: 'rgba(163, 230, 53, 0.4)',
                    borderColor: '#a3e635',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                scales: { x: { beginAtZero: true, max: 100 } }
            }
        });

        // 4. Sedes
        const ctxSede = document.getElementById('chartSedes');
        new Chart(ctxSede, {
            type: 'bar',
            data: {
                labels: {!! json_encode($sedesData->pluck('nombre')) !!},
                datasets: [{
                    label: 'Eventos',
                    data: {!! json_encode($sedesData->pluck('total')) !!},
                    backgroundColor: 'rgba(139, 92, 246, 0.4)',
                    borderColor: '#8b5cf6',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    async function exportFullPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const element = document.getElementById('allCharts');
        
        // Efecto visual de carga (opcional si es muy lento)
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generando...';
        btn.disabled = true;

        try {
            const canvas = await html2canvas(element, {
                backgroundColor: '#0a0b10', // Mantener fondo oscuro
                scale: 2
            });

            const imgData = canvas.toDataURL('image/png');
            const imgProps = doc.getImageProperties(imgData);
            const pdfWidth = doc.internal.pageSize.getWidth() - 20;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            // Header del PDF
            doc.setTextColor(255, 255, 255);
            doc.setFillColor(10, 11, 16);
            doc.rect(0, 0, 210, 297, 'F');
            
            doc.setFontSize(22);
            doc.text('EventCore - Reporte General', 10, 20);
            doc.setFontSize(14);
            doc.text('Periodo: {{ $month ? $meses[$month-1] : "Año Completo" }} {{ $year }}', 10, 30);
            doc.text('Generado el: ' + new Date().toLocaleString(), 10, 38);
            
            doc.addImage(imgData, 'PNG', 10, 50, pdfWidth, pdfHeight);
            doc.save('reporte-general-eventcore-{{ $year }}.pdf');
        } catch (e) {
            console.error(e);
            alert('Error al generar el PDF');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>
@endsection
