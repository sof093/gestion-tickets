<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentarios';
    protected $primaryKey = 'id_comentario';
    public $timestamps = false;

    // 🔥 ESTA ES LA PARTE QUE FALTA O ESTÁ MAL
    protected $fillable = [
        'id_ticket',
        'id_usuario',
        'mensaje',
        'fecha'
    ];

    // Relación con el usuario que escribió el comentario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}