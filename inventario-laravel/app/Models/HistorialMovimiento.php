<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMovimiento extends Model
{
    protected $table = 'historial_movimientos';

    protected $guarded = ['id'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
