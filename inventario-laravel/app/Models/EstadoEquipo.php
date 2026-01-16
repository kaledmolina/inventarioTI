<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoEquipo extends Model
{
    protected $table = 'estados_equipo';

    protected $guarded = ['id'];

    public $timestamps = false;
}
