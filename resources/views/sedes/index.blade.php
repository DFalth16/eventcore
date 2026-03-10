@extends('layouts.app')
@section('title', 'Sedes')
@section('page_title', 'Gestión de Sedes')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-weight:200">Listado de <strong>Sedes</strong></h2>
  <a href="/sedes/crear" class="btn btn-p"><i class="bi bi-plus-lg"></i> Nueva Sede</a>
</div>
<div class="card">
  <table class="et">
    <thead>
      <tr><th>Nombre</th><th>Ciudad / País</th><th>Dirección</th><th>Capacidad</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      @forelse($sedes as $sede)
      <tr>
        <td style="color:var(--t1);font-weight:500">{{ $sede->nombre }}</td>
        <td>{{ $sede->ciudad }}, {{ $sede->pais }}</td>
        <td style="font-size:12.5px;max-width:200px">{{ $sede->direccion }}</td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ number_format($sede->capacidad) }} personas</td>
        <td>
          <div style="display:flex;gap:8px">
            <a href="/sedes/{{ $sede->id_sede }}/editar" style="color:var(--cyan);text-decoration:none;font-size:12px">Editar</a>
            <form method="POST" action="/sedes/{{ $sede->id_sede }}" style="display:inline" onsubmit="return confirm('¿Eliminar esta sede?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:none;color:var(--rose);cursor:pointer;font-size:12px;font-family:inherit">Eliminar</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--t4)">No hay sedes registradas.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
