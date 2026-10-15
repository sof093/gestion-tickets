<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = 'subcategorias';
    protected $primaryKey = 'id_subcat';
    public $timestamps = false;

    // Relación: Una subcategoría pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_cat', 'id_cat');
    }
}