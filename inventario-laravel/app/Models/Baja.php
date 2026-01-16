<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    use HasFactory;

    protected $table = 'bajas'; // Explicit table name
    protected $guarded = ['id'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }
}
