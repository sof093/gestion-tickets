<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_cat';
    public $timestamps = false;

    // 🚀 Agregamos el $fillable para permitir guardar estos campos
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación con subcategorías (necesaria para el acordeón)
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'id_cat');
    }
}