<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoInscripcion extends Model
{
    protected $table      = 'estados_inscripcion';
    protected $primaryKey = 'id_estado_inscripcion';
    public    $timestamps = false;

    protected $fillable = ['nombre'];
}
