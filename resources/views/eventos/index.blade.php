@extends('layouts.app')
@section('title', 'Eventos')
@section('page_title', 'Gestión de Eventos')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-weight:200">Listado de <strong>Eventos</strong></h2>
  <a href="/eventos/crear" class="btn btn-p">+ Nuevo Evento</a>
</div>

<div class="card" style="margin-bottom:20px">
  <form method="GET" action="/eventos" style="display:flex;gap:10px">
    <input type="text" name="q" class="form-control" placeholder="Buscar por título, código o sede..." value="{{ $search }}" style="flex:1">
    <button type="submit" class="btn btn-g">Buscar</button>
    @if($search)
      <a href="/eventos" class="btn btn-g">✕</a>
    @endif
  </form>
</div>

<div class="card">
  <table class="et">
    <thead>
      <tr>
        <th>Evento</th><th>Sede</th><th>Fecha</th><th>Ocupación</th><th>Estado</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($eventos as $ev)
      @php
        $p  = $ev->cupo_maximo > 0 ? round($ev->inscritos*100/$ev->cupo_maximo) : 0;
        $bc = $p>=90?'var(--rose)':($p>=60?'var(--amber)':'var(--cyan)');
      @endphp
      <tr>
        <td>
          <div style="color:var(--t1)">{{ $ev->titulo }}</div>
          <div style="font-size:11px;color:var(--t4)">{{ $ev->categoria }} · {{ $ev->codigo_evento }}</div>
        </td>
        <td>{{ $ev->sede }}</td>
        <td><span style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ date('d/m/Y', strtotime($ev->fecha_inicio)) }}</span></td>
        <td>
          <span class="obar"><span class="ofill" style="width:{{ $p }}%;background:{{ $bc }}"></span></span>
          <span style="font-family:'JetBrains Mono',monospace;font-size:11px">{{ $p }}%</span>
        </td>
        <td><span class="sbg {{ strtolower($ev->estado) }}">{{ $ev->estado }}</span></td>
        <td>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="/eventos/{{ $ev->id_evento }}/inscritos" style="color:var(--lime);text-decoration:none;font-size:12px;font-weight:500">Inscritos ({{ $ev->inscritos }})</a>
            <a href="/eventos/{{ $ev->id_evento }}/editar" style="color:var(--cyan);text-decoration:none;font-size:12px">Editar</a>
            <form method="POST" action="/eventos/{{ $ev->id_evento }}" style="display:inline" onsubmit="return confirm('¿Cancelar este evento?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:none;color:var(--rose);cursor:pointer;font-size:12px;font-family:inherit">Cancelar</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--t4)">No se encontraron eventos.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
