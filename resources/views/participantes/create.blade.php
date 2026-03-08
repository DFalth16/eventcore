@extends('layouts.app')
@section('title', 'Nuevo Participante')
@section('page_title', 'Nuevo Participante')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <h2 style="font-weight:200">Crear <strong>Participante</strong></h2>
  <a href="/participantes" class="btn btn-g">← Volver</a>
</div>
@if($errors->any())
  <div class="alert alert-error">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
@endif
<div class="card" style="max-width:700px">
  <form method="POST" action="/participantes">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" required>
      </div>
      <div class="form-group">
        <label>Apellidos</label>
        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
      </div>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Teléfono (opcional)</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej. 71234567">
      </div>
      <div class="form-group">
        <label>Documento ID (opcional)</label>
        <input type="text" name="documento_id" class="form-control" value="{{ old('documento_id') }}" placeholder="Ej. 9876543">
      </div>
    </div>
    <div style="display:flex;gap:15px;margin-top:10px">
      <button type="submit" class="btn btn-p" style="flex:1">Registrar Participante</button>
      <a href="/participantes" class="btn btn-g">Cancelar</a>
    </div>
  </form>
</div>
@endsection
