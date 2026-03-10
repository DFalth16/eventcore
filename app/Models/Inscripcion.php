<?php

namespace App\Models;
//AS
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table      = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    public    $timestamps = false;

    protected $fillable = [
        'id_evento', 'id_participante', 'id_estado_inscripcion',
        'codigo_inscripcion', 'asistio', 'observaciones',
    ];

    protected $casts = ['asistio' => 'boolean'];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento', 'id_evento');
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'id_participante', 'id_participante');
    }

    public function estadoInscripcion()
    {
        return $this->belongsTo(EstadoInscripcion::class, 'id_estado_inscripcion', 'id_estado_inscripcion');
    }
}
