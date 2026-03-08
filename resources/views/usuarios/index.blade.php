@extends('layouts.app')
@section('title', 'Usuarios')
@section('page_title', 'Gestión de Usuarios')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-weight:200">Listado de <strong>Usuarios</strong></h2>
  <a href="/usuarios/crear" class="btn btn-p">+ Nuevo Usuario</a>
</div>
<div class="card">
  <table class="et">
    <thead>
      <tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Creado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      @forelse($usuarios as $u)
      <tr>
        <td style="color:var(--t1);font-weight:500">{{ $u->nombres }} {{ $u->apellidos }}</td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ $u->email }}</td>
        <td><span class="sbg" style="background:var(--cyan-gs);color:var(--cyan)">{{ $u->rol }}</span></td>
        <td>
          @if($u->activo)
            <span class="sbg activo">Activo</span>
          @else
            <span class="sbg cancelado">Inactivo</span>
          @endif
        </td>
        <td style="font-size:12px">{{ date('d/m/Y', strtotime($u->creado_en)) }}</td>
        <td>
          <div style="display:flex;gap:8px">
            <a href="/usuarios/{{ $u->id_usuario }}/editar" style="color:var(--cyan);text-decoration:none;font-size:12px">Editar</a>
            @if($u->activo)
            <form method="POST" action="/usuarios/{{ $u->id_usuario }}" style="display:inline" onsubmit="return confirm('¿Desactivar este usuario?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:none;color:var(--rose);cursor:pointer;font-size:12px;font-family:inherit">Desactivar</button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--t4)">No hay usuarios registrados.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
