<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Falla extends Model
{
    protected $table = 'fallas';
    protected $primaryKey = 'id_falla';
    public $timestamps = false;
    
    protected $fillable = ['id_subcat', 'nombre', 'descripcion', 'codigo_falla'];

    public function subcategoria() { return $this->belongsTo(Subcategoria::class, 'id_subcat'); }
}