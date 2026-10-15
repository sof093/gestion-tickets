<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id_ticket';
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function patio()
    {
        return $this->belongsTo(Patio::class, 'id_patio', 'id_patio');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_cat');
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'id_subcat', 'id_subcat');
    }

    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'id_prioridad', 'id_prioridad');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'id_tecnico', 'id_usuario');
    }

    public function adjuntos()
    {
        return $this->hasMany(Adjunto::class, 'id_ticket', 'id_ticket');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_ticket', 'id_ticket');
    }
}
