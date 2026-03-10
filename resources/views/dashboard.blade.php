@extends('layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Panel de Control')
@section('head_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endsection
@section('extra_styles')
<style>
.hero{position:relative;overflow:hidden;border-radius:var(--r);border:1px solid var(--border2);padding:40px;margin-bottom:24px;background:linear-gradient(135deg,rgba(0,212,255,.05) 0%,var(--c2) 50%,rgba(139,92,246,.04) 100%);box-shadow:0 10px 30px rgba(0,0,0,0.2)}
.sc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
.sc-card{background:var(--c1);border:1px solid var(--border);border-radius:var(--r);padding:22px 24px;position:relative;transition:all .4s var(--ease);overflow:hidden}
.sc-card:hover{transform:translateY(-8px) scale(1.02);border-color:var(--border2);background:var(--c2);box-shadow:0 15px 30px rgba(0,0,0,0.3)}
.sc-val{font-family:'JetBrains Mono',monospace;font-size:32px;font-weight:200;margin-top:10px;letter-spacing:-1px}
.stat-item{padding:12px;border-radius:10px;background:rgba(255,255,255,0.015);border:1px solid var(--border);transition:all .3s var(--ease)}
.stat-item:hover{background:rgba(255,255,255,0.03);transform:scale(1.02)}
</style>
@endsection
@section('content')
<div class="hero">
  <h1 style="font-weight:200;font-size:32px">Bienvenido de nuevo, <strong style="font-weight:500;color:var(--cyan);text-shadow:0 0 20px rgba(0,212,255,0.2)">{{ auth('admin')->user()->nombres }}</strong></h1>
  <p style="font-size:14px;color:var(--t2);margin-top:12px;max-width:500px">Gestiona tus eventos de manera eficiente con datos precisos y en tiempo real.</p>
</div>

<div class="sc-grid">
  <div class="sc-card">
    <div style="font-size:10px;color:var(--t3);letter-spacing:1px">TOTAL EVENTOS</div>
    <div class="sc-val">{{ $stats['total_eventos'] }}</div>
    <div style="position:absolute;right:20px;top:20px;opacity:0.1;font-size:24px"><i class="bi bi-calendar-event"></i></div>
  </div>
  <div class="sc-card">
    <div style="font-size:10px;color:var(--t3);letter-spacing:1px">EVENTOS ACTIVOS</div>
    <div class="sc-val" style="color:var(--lime)">{{ $stats['eventos_activos'] }}</div>
    <div style="position:absolute;right:20px;top:20px;opacity:0.1;font-size:24px"><i class="bi bi-lightning-fill"></i></div>
  </div>
  <div class="sc-card">
    <div style="font-size:10px;color:var(--t3);letter-spacing:1px">PARTICIPANTES</div>
    <div class="sc-val" style="color:var(--amber)">{{ $stats['total_asistentes'] }}</div>
    <div style="position:absolute;right:20px;top:20px;opacity:0.1;font-size:24px"><i class="bi bi-people"></i></div>
  </div>
  <div class="sc-card">
    <div style="font-size:10px;color:var(--t3);letter-spacing:1px">SEDES DISPONIBLES</div>
    <div class="sc-val" style="color:var(--cyan)">{{ $stats['total_sedes'] }}</div>
    <div style="position:absolute;right:20px;top:20px;opacity:0.1;font-size:24px"><i class="bi bi-geo-alt"></i></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px">
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
      <h3 style="font-size:14px;font-weight:500;color:var(--t1)">Eventos Estratégicos</h3>
      <a href="/eventos" style="font-size:11px;color:var(--cyan);text-decoration:none">Ver todos <i class="bi bi-arrow-right-short"></i></a>
    </div>
    <table class="et">
      <thead><tr><th>Evento / Categoría</th><th>Fecha</th><th>Ocupación</th><th>Estado</th></tr></thead>
      <tbody>
        @foreach($eventos as $ev)
        @php $p = $ev->cupo > 0 ? round($ev->inscritos*100/$ev->cupo) : 0; @endphp
        <tr>
          <td>
            <div style="font-weight:500;color:var(--t1)">{{ $ev->nombre }}</div>
            <div style="font-size:11px;color:var(--t4);margin-top:2px">{{ $ev->cat }}</div>
          </td>
          <td><div style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ date('d/m/Y', strtotime($ev->fecha)) }}</div></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="width:60px;height:4px;background:rgba(255,255,255,0.05);border-radius:2px;overflow:hidden">
                <div style="width:{{ $p }}%;height:100%;background:var(--cyan);box-shadow:0 0 10px var(--cyan)"></div>
              </div>
              <span style="font-size:11px;font-family:'JetBrains Mono'">{{ $p }}%</span>
            </div>
          </td>
          <td><span class="sbg {{ strtolower($ev->estado) }}">{{ $ev->estado }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="card">
    <h3 style="font-size:14px;font-weight:500;color:var(--t1);margin-bottom:20px">Flujo de Actividad</h3>
    <div style="display:flex;flex-direction:column;gap:12px">
      @foreach($actividad as $i => $a)
      <div class="stat-item">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div style="font-weight:500;font-size:12.5px;color:var(--t1)">{{ $a->accion }}</div>
          <div style="font-family:'JetBrains Mono';font-size:10px;color:var(--t4)">{{ date('H:i', strtotime($a->fecha)) }}</div>
        </div>
        <div style="color:var(--t2);font-size:11.5px;margin-top:4px">{{ $a->det }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
