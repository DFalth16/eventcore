@extends('layouts.app')
@section('title', 'Reporte Detallado — ' . $label)
@section('page_title', $label)

@section('head_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection

@section('extra_styles')
<style>
.detail-card{background:var(--c1);border:1px solid var(--border);border-radius:var(--r);padding:30px;margin-bottom:24px}
.table-container { overflow-x: auto; }
.badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
.badge-blue { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
.badge-green { background: rgba(34, 197, 94, 0.1); color: #4ade80; }
.badge-red { background: rgba(239, 68, 68, 0.1); color: #f87171; }
.badge-orange { background: rgba(245, 158, 11, 0.1); color: #fbbf24; }
</style>
@endsection

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <div style="display:flex;align-items:center;gap:15px">
    <a href="/reportes?year={{ $year }}" class="btn btn-g"><i class="bi bi-arrow-left"></i> Volver</a>
    <h2 style="font-weight:200;margin:0">{{ $label }} <span style="color:var(--t4)">· {{ $year }}</span></h2>
  </div>
  <button onclick="exportFullPDF()" class="btn btn-p"><i class="bi bi-file-pdf"></i> Exportar a PDF</button>
</div>

<div class="detail-card" id="reportContent">
  <div class="table-container">
    <table class="et">
      <thead>
        <tr>
          <th>Código</th>
          <th>Título</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Estado</th>
          <th>Categoría</th>
          <th>Sede</th>
          <th style="text-align:center">Cupo</th>
          <th style="text-align:center">Inscritos</th>
          <th style="text-align:center">% Ocup.</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $ev)
        <tr>
          <td style="font-family:'JetBrains Mono'; font-size:11px">{{ $ev->codigo_evento }}</td>
          <td style="font-weight:500">{{ $ev->titulo }}</td>
          <td style="font-size:12px; white-space:nowrap">{{ date('d/m/Y', strtotime($ev->fecha_inicio)) }}</td>
          <td style="font-size:12px; white-space:nowrap">{{ date('d/m/Y', strtotime($ev->fecha_fin)) }}</td>
          <td>
            <span class="badge {{ $ev->estado == 'Activo' ? 'badge-green' : ($ev->estado == 'Cancelado' ? 'badge-red' : 'badge-blue') }}">
              {{ $ev->estado }}
            </span>
          </td>
          <td style="font-size:12px">{{ $ev->categoria }}</td>
          <td style="font-size:12px">{{ $ev->sede }}</td>
          <td style="text-align:center">{{ $ev->cupo_maximo }}</td>
          <td style="text-align:center">{{ $ev->total_inscritos }}</td>
          <td style="text-align:center">
            <div style="font-weight:600; color: {{ $ev->porcentaje_ocupacion >= 90 ? '#f87171' : ($ev->porcentaje_ocupacion >= 50 ? '#4ade80' : 'var(--cyan)') }}">
              {{ $ev->porcentaje_ocupacion }}%
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<script>
    async function exportFullPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape for many columns
        const element = document.getElementById('reportContent');
        
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generando...';
        btn.disabled = true;

        try {
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
            doc.rect(0, 0, 297, 210, 'F');
            
            doc.setFontSize(22);
            doc.text('EventCore - {{ $label }}', 10, 20);
            doc.setFontSize(14);
            doc.text('Periodo: {{ $year }}', 10, 30);
            doc.text('Generado el: ' + new Date().toLocaleString(), 10, 38);
            
            doc.addImage(imgData, 'PNG', 10, 50, pdfWidth, pdfHeight);
            doc.save('reporte-detallado-eventos-{{ $year }}.pdf');
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
