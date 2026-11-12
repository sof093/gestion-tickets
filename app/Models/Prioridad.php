<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    protected $table = 'prioridades';
    protected $primaryKey = 'id_prioridad';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'nivel',
        'descripcion',
        'tiempo_respuesta_min',
        'tiempo_resolucion_min',
        'color',
        'estatus'
    ];
}