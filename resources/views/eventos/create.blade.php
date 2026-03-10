@extends('layouts.app')
@section('title', 'Crear Evento')
@section('page_title', 'Crear Evento')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
  <h2 style="font-weight:200">Crear <strong>Evento</strong></h2>
  <a href="/eventos" class="btn btn-g"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

@if($errors->any())
  <div class="alert alert-error">
    @foreach($errors->all() as $err)<div><i class="bi bi-dot"></i> {{ $err }}</div>@endforeach
  </div>
@endif

<div class="card" style="max-width:800px">
  <form method="POST" action="/eventos">
    @csrf
    <div class="form-group">
      <label>Título del Evento</label>
      <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
    </div>
    <div class="form-group">
      <label>Descripción</label>
      <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion') }}</textarea>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Categoría</label>
        <select name="id_categoria" class="form-control" required>
          <option value="">Seleccionar...</option>
          @foreach($categorias as $cat)
            <option value="{{ $cat->id_categoria }}" {{ old('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>{{ $cat->nombre }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Sede</label>
        <select name="id_sede" class="form-control" required>
          <option value="">Seleccionar...</option>
          @foreach($sedes as $sede)
            <option value="{{ $sede->id_sede }}" {{ old('id_sede') == $sede->id_sede ? 'selected' : '' }}>{{ $sede->nombre }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Estado</label>
        <select name="id_estado" class="form-control">
          @foreach($estados as $est)
            <option value="{{ $est->id_estado }}" {{ old('id_estado', 1) == $est->id_estado ? 'selected' : '' }}>{{ $est->nombre }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Cupo Máximo</label>
        <input type="number" name="cupo_maximo" class="form-control" value="{{ old('cupo_maximo') }}" min="1" required>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Fecha Inicio</label>
        <input type="datetime-local" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
      </div>
      <div class="form-group">
        <label>Fecha Fin</label>
        <input type="datetime-local" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
      <div class="form-group">
        <label>Precio Entrada (Bs.)</label>
        <input type="number" name="precio_entrada" id="precioInput" class="form-control" step="0.01" min="0" value="{{ old('precio_entrada', 0) }}">
      </div>
      <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:6px">
        <label style="display:flex;align-items:center;gap:10px;text-transform:none;letter-spacing:normal">
          <input type="checkbox" name="es_gratuito" id="gratuitoChk" onchange="document.getElementById('precioInput').disabled=this.checked; if(this.checked)document.getElementById('precioInput').value='0';" {{ old('es_gratuito') ? 'checked' : '' }}>
          <span style="font-size:14px">Evento Gratuito</span>
        </label>
      </div>
    </div>
    <div style="display:flex;gap:15px;margin-top:10px">
      <button type="submit" class="btn btn-p" style="flex:1">Crear Evento</button>
      <a href="/eventos" class="btn btn-g">Cancelar</a>
    </div>
  </form>
</div>
@endsection
