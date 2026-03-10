@extends('layouts.app')
@section('title', 'Inscritos — ' . $evento->titulo)
@section('page_title', 'Inscritos · ' . $evento->titulo)
@section('extra_styles')
<style>
.stat-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px}
.stat{background:var(--c1);border:1px solid var(--border);border-radius:var(--r);padding:20px 24px}
.stat-val{font-size:32px;font-weight:200;line-height:1;font-family:'JetBrains Mono',monospace}
.stat-lbl{font-size:11px;color:var(--t3);text-transform:uppercase;letter-spacing:1px;margin-top:4px}
.aforo-bar{height:8px;border-radius:4px;background:rgba(255,255,255,.06);overflow:hidden;margin-top:10px}
.aforo-fill{height:100%;border-radius:4px;transition:width 1s var(--ease)}
.avatar{width:32px;height:32px;border-radius:50%;background:var(--cyan-g);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--cyan);border:1px solid var(--border2);flex-shrink:0}
</style>
@endsection
@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px">
  <div>
    <h2 style="font-weight:200;font-size:24px">Lista de <strong style="color:var(--cyan)">Inscritos</strong></h2>
    <div style="font-size:13px;color:var(--t3);margin-top:4px"><i class="bi bi-geo-alt"></i> {{ $evento->sede }} &nbsp;·&nbsp; <i class="bi bi-calendar3"></i> {{ date('d/m/Y', strtotime($evento->fecha_inicio)) }}</div>
  </div>
  <a href="/eventos" class="btn btn-g"><i class="bi bi-arrow-left"></i> Volver a Eventos</a>
</div>

@php
  $pct = $evento->cupo_maximo > 0 ? round(($evento->total_inscritos / $evento->cupo_maximo) * 100) : 0;
@endphp

<div class="stat-cards">
  <div class="stat">
    <div class="stat-val" style="color:var(--cyan)">{{ (int)$evento->total_inscritos }}</div>
    <div class="stat-lbl">Inscritos</div>
    <div class="aforo-bar">
      <div class="aforo-fill" style="width:{{ $pct }}%;background:{{ $pct>=90?'var(--rose)':($pct>=70?'var(--amber)':'var(--cyan)') }}"></div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-val" style="color:var(--lime)">{{ (int)$evento->cupo_maximo }}</div>
    <div class="stat-lbl">Cupo Máximo</div>
  </div>
  <div class="stat">
    <div class="stat-val" style="color:{{ $pct>=100?'var(--rose)':'var(--amber)' }}">{{ max(0, (int)$evento->cupo_maximo - (int)$evento->total_inscritos) }}</div>
    <div class="stat-lbl">Plazas Disponibles</div>
  </div>
</div>

<div class="card" style="margin-bottom:24px">
  <div style="font-size:14px;font-weight:500;margin-bottom:16px;color:var(--t1)"><i class="bi bi-pencil-square"></i> Inscribir Participante Manualmente</div>
  <p style="font-size:12.5px;color:var(--t3);margin-bottom:16px">Ingrese el email de un participante ya registrado en el sistema para inscribirlo.</p>

  @if(!empty($errors))
    <div class="alert alert-error">
      @foreach($errors as $err)<div>• {{ $err }}</div>@endforeach
    </div>
  @endif

  @if((int)$evento->total_inscritos < (int)$evento->cupo_maximo)
  <form method="POST" action="/eventos/{{ $evento->id_evento }}/inscritos" style="display:flex;gap:12px;align-items:flex-end">
    @csrf
    <div style="flex:1">
      <label style="font-size:11px;color:var(--t4);text-transform:uppercase;letter-spacing:1.2px;display:block;margin-bottom:8px">Email del Participante</label>
      <input type="email" name="email_participante" class="form-control" placeholder="Ej. pedro@email.com" required>
    </div>
    <button type="submit" class="btn btn-p" style="height:44px">Inscribir</button>
  </form>
  <div style="margin-top:12px;font-size:12px;color:var(--t3)">
    ¿El participante no está registrado? <a href="/participantes/crear" style="color:var(--cyan)">Crear participante <i class="bi bi-arrow-right-short"></i></a>
  </div>
  @else
  <div style="color:var(--rose);font-size:13px;padding:14px;background:var(--rose-g);border-radius:10px;border:1px solid rgba(255,77,109,.2)">
    <i class="bi bi-ban"></i> Este evento ha alcanzado su aforo máximo de {{ (int)$evento->cupo_maximo }} personas.
  </div>
  @endif
</div>

<div class="card">
  <div style="font-size:14px;font-weight:500;margin-bottom:20px;color:var(--t1)">Listado de Participantes Inscritos</div>
  @if($inscritos->isEmpty())
    <div style="text-align:center;padding:50px 20px;color:var(--t3)">
      <div style="font-size:40px;margin-bottom:12px;opacity:.4"><i class="bi bi-clipboard-data"></i></div>
      <div>No hay participantes inscritos en este evento aún.</div>
    </div>
  @else
  <table class="et">
    <thead>
      <tr><th>Participante</th><th>Email</th><th>Teléfono</th><th>Código</th><th>Estado</th><th>Fecha Inscripción</th></tr>
    </thead>
    <tbody>
      @foreach($inscritos as $ins)
      @php
        $initials = strtoupper(mb_substr($ins->nombres,0,1) . mb_substr($ins->apellidos,0,1));
        $estColor = ['Pendiente'=>'var(--amber)','Confirmada'=>'var(--lime)','Cancelada'=>'var(--rose)','Lista de espera'=>'var(--violet)'][$ins->estado_inscripcion] ?? 'var(--t2)';
      @endphp
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div class="avatar">{{ $initials }}</div>
            <div style="font-weight:500;color:var(--t1)">{{ $ins->nombres }} {{ $ins->apellidos }}</div>
          </div>
        </td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ $ins->email }}</td>
        <td style="font-size:12.5px">{{ $ins->telefono ?: '—' }}</td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--cyan)">{{ $ins->codigo_inscripcion }}</td>
        <td><span style="color:{{ $estColor }};font-size:11px;font-weight:600">{{ $ins->estado_inscripcion }}</span></td>
        <td style="font-size:12px">{{ date('d/m/Y H:i', strtotime($ins->fecha_inscripcion)) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif
</div>
@endsection
