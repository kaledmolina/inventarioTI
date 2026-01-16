<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    protected $table = 'bajas';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'fecha_baja' => 'date',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }
}
