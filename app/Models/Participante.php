<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $table      = 'participantes';
    protected $primaryKey = 'id_participante';
    public    $timestamps = false;

    protected $fillable = ['nombres', 'apellidos', 'email', 'telefono', 'documento_id'];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_participante', 'id_participante');
    }
}
