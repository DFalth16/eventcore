@extends('layouts.app')
@section('title', 'Detalle Reporte — ' . $label)
@section('page_title', $label)
@section('head_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection
@section('extra_styles')
<style>
.detail-card{background:var(--c1);border:1px solid var(--border);border-radius:var(--r);padding:30px;margin-bottom:24px}
.chart-box{height:500px;margin-bottom:30px}
</style>
@endsection
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <div style="display:flex;align-items:center;gap:15px">
    <a href="/reportes?year={{ $year }}" class="btn btn-g"><i class="bi bi-arrow-left"></i> Volver</a>
    <h2 style="font-weight:200;margin:0">{{ $label }} <span style="color:var(--t4)">· {{ $year }}</span></h2>
  </div>
  <button onclick="exportSinglePDF()" class="btn btn-p"><i class="bi bi-file-pdf"></i> Exportar a PDF</button>
</div>

<div class="detail-card" id="reportContent">
  <div class="chart-box">
    <canvas id="detChart"></canvas>
  </div>

  <table class="et">
    <thead>
      <tr>
        <th>{{ $tipo == 'categorias' ? 'Categoría' : ($tipo == 'inscripciones' ? 'Mes' : ($tipo == 'ocupacion' ? 'Evento' : 'Sede')) }}</th>
        <th>{{ $tipo == 'ocupacion' ? 'Ocupación (%)' : 'Total' }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $d)
      <tr>
        <td>{{ $d->nombre ?? $d->mes ?? $d->titulo }}</td>
        <td style="font-family:'JetBrains Mono'">{{ $d->total ?? $d->porcentaje . '%' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script>
    const { jsPDF } = window.jspdf;
    const ctx = document.getElementById('detChart');
    const type = '{{ $tipo }}';
    const chartLabels = {!! json_encode($data->map(fn($d) => $d->nombre ?? $d->mes ?? $d->titulo)) !!};
    const chartValues = {!! json_encode($data->map(fn($d) => $d->total ?? $d->porcentaje)) !!};

    let chartType = 'bar';
    let bgColors = 'rgba(0, 212, 255, 0.4)';
    let borderColors = 'var(--cyan)';

    if(type === 'categorias') chartType = 'doughnut';
    if(type === 'inscripciones') chartType = 'line';

    new Chart(ctx, {
        type: chartType,
        data: {
            labels: chartLabels,
            datasets: [{
                label: '{{ $label }}',
                data: chartValues,
                backgroundColor: chartType === 'doughnut' ? ['#00d4ff', '#8b5cf6', '#bef264', '#fb7185', '#fbbf24'] : bgColors,
                borderColor: chartType === 'doughnut' ? 'transparent' : borderColors,
                borderWidth: 1,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: type === 'categorias', position: 'bottom' } },
            scales: chartType !== 'doughnut' ? { y: { beginAtZero: true } } : {}
        }
    });

    async function exportSinglePDF() {
        const doc = new jsPDF('p', 'mm', 'a4');
        const element = document.getElementById('reportContent');
        
        const canvas = await html2canvas(element, {
            backgroundColor: '#0a0b10',
            scale: 2
        });
        
        const imgData = canvas.toDataURL('image/png');
        const imgProps = doc.getImageProperties(imgData);
        const pdfWidth = doc.internal.pageSize.getWidth() - 20;
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
        
        doc.setTextColor(255, 255, 255);
        doc.setFillColor(10, 11, 16);
        doc.rect(0, 0, 210, 297, 'F');
        
        doc.setFontSize(22);
        doc.text('EventCore - Reporte Detallado', 10, 20);
        doc.setFontSize(14);
        doc.text('Reporte: {{ $label }} ({{ $year }})', 10, 30);
        doc.text('Generado el: ' + new Date().toLocaleString(), 10, 38);
        
        doc.addImage(imgData, 'PNG', 10, 50, pdfWidth, pdfHeight);
        doc.save('reporte-{{ $tipo }}-{{ $year }}.pdf');
    }
</script>
@endsection
