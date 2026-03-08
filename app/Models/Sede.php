<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table      = 'sedes';
    protected $primaryKey = 'id_sede';
    public    $timestamps = false;

    protected $fillable = ['nombre', 'direccion', 'ciudad', 'pais', 'capacidad', 'referencia'];

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_sede', 'id_sede');
    }
}
