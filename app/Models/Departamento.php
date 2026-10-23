<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';
    public $timestamps = false;

    // Habilitar la asignación masiva para estos campos
    protected $fillable = [
        'nombre',
        'segmento',
        'estatus', // <-- Agregado para que permita guardar el Estatus
    ];
}