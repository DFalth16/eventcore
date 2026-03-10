@extends('layouts.app')
@section('title', 'Nueva Sede')
@section('page_title', 'Nueva Sede')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <h2 style="font-weight:200">Crear <strong>Sede</strong></h2>
  <a href="/sedes" class="btn btn-g"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
@if($errors->any())
  <div class="alert alert-error">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
@endif
<div class="card" style="max-width:700px">
  <form method="POST" action="/sedes">
    @csrf
    <div class="form-group">
      <label>Nombre de la Sede</label>
      <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
    </div>
    <div class="form-group">
      <label>Dirección</label>
      <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" required>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Ciudad</label>
        <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad') }}" required>
      </div>
      <div class="form-group">
        <label>País</label>
        <input type="text" name="pais" class="form-control" value="{{ old('pais', 'Bolivia') }}">
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Capacidad (personas)</label>
        <input type="number" name="capacidad" class="form-control" value="{{ old('capacidad') }}" min="1" required>
      </div>
      <div class="form-group">
        <label>Referencia (opcional)</label>
        <input type="text" name="referencia" class="form-control" value="{{ old('referencia') }}" placeholder="Ej. Frente al parque">
      </div>
    </div>
    <div style="display:flex;gap:15px;margin-top:10px">
      <button type="submit" class="btn btn-p" style="flex:1">Crear Sede</button>
      <a href="/sedes" class="btn btn-g">Cancelar</a>
    </div>
  </form>
</div>
@endsection
