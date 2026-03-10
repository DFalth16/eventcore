<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UsuarioAdmin extends Authenticatable
{
    use Notifiable;

    protected $table      = 'usuarios_admin';
    protected $primaryKey = 'id_usuario';
    public    $timestamps = false;

    protected $fillable = [
        'id_rol', 'nombres', 'apellidos', 'email', 'password_hash', 'activo', 'api_token',
    ];

    protected $hidden = ['password_hash', 'api_token'];

    // Alias para que Laravel Auth use password_hash
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_organizador', 'id_usuario');
    }
}
