<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoEvento extends Model
{
    protected $table      = 'estados_evento';
    protected $primaryKey = 'id_estado';
    public    $timestamps = false;

    protected $fillable = ['nombre', 'descripcion'];
}
