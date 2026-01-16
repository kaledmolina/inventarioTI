<?php

namespace App\Services;

use App\Models\HistorialMovimiento;
use Illuminate\Support\Facades\Auth;

class HistorialService
{
    /**
     * Registrar una acción en el historial.
     *
     * @param int|null $equipoId ID del equipo afectado
     * @param string $accion Tipo de acción (ASIGNACION, REPARACION, etc.)
     * @param string|null $detalles Detalles adicionales o resumen
     * @return HistorialMovimiento
     */
    public static function registrar($equipoId, $accion, $detalles = null)
    {
        return HistorialMovimiento::create([
            'id_equipo' => $equipoId,
            'id_user' => Auth::id(), // Usuario autenticado actual
            'accion' => strtoupper($accion),
            'detalles' => $detalles,
        ]);
    }
}
