<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table      = 'eventos';
    protected $primaryKey = 'id_evento';
    public    $timestamps = false;

    protected $fillable = [
        'id_categoria', 'id_estado', 'id_sede', 'id_organizador',
        'codigo_evento', 'titulo', 'descripcion',
        'fecha_inicio', 'fecha_fin', 'cupo_maximo',
        'precio_entrada', 'es_gratuito', 'imagen_url',
    ];

    protected $casts = [
        'es_gratuito'   => 'boolean',
        'precio_entrada' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaEvento::class, 'id_categoria', 'id_categoria');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoEvento::class, 'id_estado', 'id_estado');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede', 'id_sede');
    }

    public function organizador()
    {
        return $this->belongsTo(UsuarioAdmin::class, 'id_organizador', 'id_usuario');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_evento', 'id_evento');
    }
}
