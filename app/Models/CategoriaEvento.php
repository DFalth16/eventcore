<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEvento extends Model
{
    protected $table      = 'categorias_evento';
    protected $primaryKey = 'id_categoria';
    public    $timestamps = false;

    protected $fillable = ['nombre', 'descripcion'];
}
