<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    protected $table = 'tipos_equipo';
    protected $guarded = ['id'];
}

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    protected $guarded = ['id'];
}
