<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patio extends Model
{
    protected $table = 'patios';
    protected $primaryKey = 'id_patio';
    public $timestamps = false;

    // Habilitar la asignación masiva para estos campos
    protected $fillable = [
        'nombre',
        'ciudad',
        'tipo',    // <-- Agregado para que permita guardar el Tipo
        'estatus', // <-- Agregado para que permita guardar el Estatus
    ];

    // Relación con Usuarios (opcional, pero útil para después)
    public function users()
    {
        return $this->hasMany(User::class, 'id_patio_descripcion', 'id_patio');
    }
}