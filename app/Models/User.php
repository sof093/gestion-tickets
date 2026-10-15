<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';          // Tu tabla real en la BD
    protected $primaryKey = 'id_usuario';   // Tu llave primaria real
    
    public $timestamps = false;             // Como usas 'fecha_registro'

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

    // Mapeo de la contraseña personalizada para Laravel Auth
    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    // ==========================================
    // RELACIONES CON LAS TABLAS DE TU BASE DE DATOS
    // ==========================================

    // Relación con Roles (para que $user->rol funcione en el LoginController)
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Relación con Patios
    public function patio()
    {
        return $this->belongsTo(Patio::class, 'id_patio_descripcion', 'id_patio');
    }

    // Relación con Departamentos
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }
}