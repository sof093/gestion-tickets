<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patio extends Model
{
    protected $table = 'patios';
    protected $primaryKey = 'id_patio';
    public $timestamps = false;
}