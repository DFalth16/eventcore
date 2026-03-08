@extends('layouts.app')
@section('title', 'Editar Sede')
@section('page_title', 'Editar Sede')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <h2 style="font-weight:200">Editar <strong>Sede</strong></h2>
  <a href="/sedes" class="btn btn-g">← Volver</a>
</div>
@if($errors->any())
  <div class="alert alert-error">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
@endif
<div class="card" style="max-width:700px">
  <form method="POST" action="/sedes/{{ $sede->id_sede }}">
    @csrf @method('PUT')
    <div class="form-group">
      <label>Nombre de la Sede</label>
      <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $sede->nombre) }}" required>
    </div>
    <div class="form-group">
      <label>Dirección</label>
      <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $sede->direccion) }}" required>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Ciudad</label>
        <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $sede->ciudad) }}" required>
      </div>
      <div class="form-group">
        <label>País</label>
        <input type="text" name="pais" class="form-control" value="{{ old('pais', $sede->pais) }}">
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Capacidad (personas)</label>
        <input type="number" name="capacidad" class="form-control" value="{{ old('capacidad', $sede->capacidad) }}" min="1" required>
      </div>
      <div class="form-group">
        <label>Referencia (opcional)</label>
        <input type="text" name="referencia" class="form-control" value="{{ old('referencia', $sede->referencia) }}">
      </div>
    </div>
    <div style="display:flex;gap:15px;margin-top:10px">
      <button type="submit" class="btn btn-p" style="flex:1">Guardar Cambios</button>
      <a href="/sedes" class="btn btn-g">Cancelar</a>
    </div>
  </form>
</div>
@endsection
