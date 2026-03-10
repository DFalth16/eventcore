@extends('layouts.app')
@section('title', 'Usuarios')
@section('page_title', 'Gestión de Usuarios')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-weight:200">Listado de <strong>Usuarios</strong></h2>
  <a href="/usuarios/crear" class="btn btn-p"><i class="bi bi-plus-lg"></i> Nuevo Usuario</a>
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
          <div style="display:flex;gap:8px;align-items:center">
            <a href="/usuarios/{{ $u->id_usuario }}/editar" style="color:var(--cyan);text-decoration:none;font-size:12px" title="Editar">
              <i class="bi bi-pencil-square"></i>
            </a>

            <!-- Toggle Status -->
            <form method="POST" action="/usuarios/{{ $u->id_usuario }}/status" style="display:inline">
              @csrf @method('PATCH')
              <button type="submit" style="background:none;border:none;color:{{ $u->activo ? 'var(--amber)' : 'var(--lime)' }};cursor:pointer;font-size:16px;padding:0" title="{{ $u->activo ? 'Desactivar' : 'Activar' }}">
                <i class="bi {{ $u->activo ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
              </button>
            </form>

            <!-- Delete (Solo si no es el usuario actual) -->
            @if(auth('admin')->id() != $u->id_usuario)
            <form method="POST" action="/usuarios/{{ $u->id_usuario }}" style="display:inline" onsubmit="return confirm('¿ELIMINAR PERMANENTEMENTE a este usuario? Esta acción no se puede deshacer.')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:none;color:var(--rose);cursor:pointer;font-size:14px;padding:0" title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>
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
