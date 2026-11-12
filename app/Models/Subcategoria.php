<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = 'subcategorias';
    protected $primaryKey = 'id_subcat';
    public $timestamps = false;
    
    protected $fillable = ['id_cat', 'nombre', 'descripcion'];

    // En app/Models/Subcategoria.php
public function categoria() 
{ 
    return $this->belongsTo(Categoria::class, 'id_cat'); 
}
    public function fallas() { return $this->hasMany(Falla::class, 'id_subcat'); }
}