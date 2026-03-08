@extends('layouts.app')
@section('title', 'Nuevo Usuario')
@section('page_title', 'Nuevo Usuario')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <h2 style="font-weight:200">Crear <strong>Usuario</strong></h2>
  <a href="/usuarios" class="btn btn-g">← Volver</a>
</div>
@if($errors->any())
  <div class="alert alert-error">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
@endif
<div class="card" style="max-width:700px">
  <form method="POST" action="/usuarios">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" required>
      </div>
      <div class="form-group">
        <label>Apellidos</label>
        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}">
      </div>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="Mínimo 5 caracteres" required>
      </div>
      <div class="form-group">
        <label>Rol</label>
        <select name="id_rol" class="form-control" required>
          <option value="">Seleccionar...</option>
          @foreach($roles as $rol)
            <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>{{ $rol->nombre_rol }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div style="display:flex;gap:15px;margin-top:10px">
      <button type="submit" class="btn btn-p" style="flex:1">Crear Usuario</button>
      <a href="/usuarios" class="btn btn-g">Cancelar</a>
    </div>
  </form>
</div>
@endsection
