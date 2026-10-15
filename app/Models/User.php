<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'num_nomina',
        'nombre_completo',
        'telefono',
        'correo',
        'contrasena_hash',
        'id_rol',
        'id_patio_descripcion',
        'id_departamento',
        'estatus',
    ];

    protected $hidden = [
        'contrasena_hash',
    ];

    // Mapeo de contraseña personalizada
    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    // Relación con el Modelo Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}