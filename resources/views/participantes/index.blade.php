@extends('layouts.app')
@section('title', 'Participantes')
@section('page_title', 'Gestión de Participantes')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-weight:200">Listado de <strong>Participantes</strong></h2>
  <a href="/participantes/crear" class="btn btn-p">+ Nuevo Participante</a>
</div>

<div class="card" style="margin-bottom:20px">
  <form method="GET" action="/participantes" style="display:flex;gap:10px">
    <input type="text" name="q" class="form-control" placeholder="Buscar por nombre, email o documento..." value="{{ $search }}" style="flex:1">
    <button type="submit" class="btn btn-g">Buscar</button>
    @if($search)<a href="/participantes" class="btn btn-g">✕</a>@endif
  </form>
</div>

<div class="card">
  <table class="et">
    <thead>
      <tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Documento</th><th>Registrado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      @forelse($participantes as $p)
      <tr>
        <td style="color:var(--t1);font-weight:500">{{ $p->nombres }} {{ $p->apellidos }}</td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ $p->email }}</td>
        <td style="font-size:12.5px">{{ $p->telefono ?: '—' }}</td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ $p->documento_id ?: '—' }}</td>
        <td style="font-size:12px">{{ date('d/m/Y', strtotime($p->creado_en)) }}</td>
        <td>
          <div style="display:flex;gap:8px">
            <a href="/participantes/{{ $p->id_participante }}/editar" style="color:var(--cyan);text-decoration:none;font-size:12px">Editar</a>
            <form method="POST" action="/participantes/{{ $p->id_participante }}" style="display:inline" onsubmit="return confirm('¿Eliminar este participante?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:none;color:var(--rose);cursor:pointer;font-size:12px;font-family:inherit">Eliminar</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--t4)">No se encontraron participantes.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
