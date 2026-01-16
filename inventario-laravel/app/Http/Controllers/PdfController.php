<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Baja;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function generarActaEntrega($id)
    {
        $asignacion = Asignacion::with(['equipo.sucursal', 'equipo.tipo_equipo', 'equipo.marca', 'equipo.modelo', 'empleado.area', 'empleado.cargo', 'empleado.sucursal'])
            ->findOrFail($id);

        $data = [
            'asignacion' => $asignacion,
            'equipo' => $asignacion->equipo,
            'empleado' => $asignacion->empleado,
            'user' => Auth::user(),
            'fecha' => $asignacion->fecha_entrega
        ];

        $pdf = Pdf::loadView('pdf.acta_entrega', $data);
        return $pdf->stream('Acta_Entrega_' . $asignacion->equipo->codigo_inventario . '.pdf');
    }

    public function generarActaDevolucion($id)
    {
        $asignacion = Asignacion::with(['equipo.sucursal', 'equipo.tipo_equipo', 'equipo.marca', 'equipo.modelo', 'empleado.area', 'empleado.cargo', 'empleado.sucursal'])
            ->findOrFail($id);

        if (!$asignacion->fecha_devolucion) {
            abort(404, 'Esta asignación no ha sido finalizada.');
        }

        $data = [
            'asignacion' => $asignacion,
            'equipo' => $asignacion->equipo,
            'empleado' => $asignacion->empleado,
            'user' => Auth::user(),
            'fecha' => $asignacion->fecha_devolucion
        ];

        $pdf = Pdf::loadView('pdf.acta_devolucion', $data);
        return $pdf->stream('Acta_Devolucion_' . $asignacion->equipo->codigo_inventario . '.pdf');
    }

    public function actaBaja(Request $request)
    {
        $request->validate([
            'id_equipo' => 'required|exists:equipos,id',
            'motivo' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $equipo = Equipo::with(['marca', 'modelo', 'tipo_equipo', 'sucursal'])->findOrFail($request->id_equipo);

        $data = [
            'equipo' => $equipo,
            'fecha' => date('d/m/Y'),
            'motivo' => $request->motivo,
            'observaciones' => $request->observaciones,
        ];

        $pdf = Pdf::loadView('pdf.acta_baja', $data);
        return $pdf->stream('acta_baja_' . $equipo->codigo_inventario . '.pdf');
    }
}
