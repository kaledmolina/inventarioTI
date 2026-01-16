<?php

namespace App\Livewire\Asignaciones;

use App\Models\Asignacion;
use App\Models\Equipo;
use App\Models\Reparacion;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Devolver extends Component
{
    use WithFileUploads;

    public Asignacion $asignacion;

    // Form Fields
    public $fecha_devolucion;
    public $estado_recibido = 'Bueno';
    public $observaciones_devolucion = '';
    public $estado_final_equipo = 'Disponible';

    // Uploads
    public $foto1;
    public $foto2;
    public $foto3;

    public function mount($id)
    {
        $this->asignacion = Asignacion::with(['equipo', 'empleado'])->findOrFail($id);

        // Validation: Cannot return if already returned
        if ($this->asignacion->fecha_devolucion) {
            return redirect()->route('asignaciones.index');
        }

        $this->fecha_devolucion = Carbon::now()->format('Y-m-d\TH:i');
    }

    public function save()
    {
        $this->validate([
            'fecha_devolucion' => 'required',
            'estado_recibido' => 'required',
            'observaciones_devolucion' => 'required',
            'estado_final_equipo' => 'required|in:Disponible,En Reparación',
            'foto1' => 'nullable|image|max:2048', // 2MB Max
            'foto2' => 'nullable|image|max:2048',
            'foto3' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () {
            // 1. Process File Uploads
            $paths = ['imagen_devolucion_1' => null, 'imagen_devolucion_2' => null, 'imagen_devolucion_3' => null];

            if ($this->foto1) {
                $paths['imagen_devolucion_1'] = $this->foto1->store('devoluciones/' . $this->asignacion->id, 'public');
            }
            if ($this->foto2) {
                $paths['imagen_devolucion_2'] = $this->foto2->store('devoluciones/' . $this->asignacion->id, 'public');
            }
            if ($this->foto3) {
                $paths['imagen_devolucion_3'] = $this->foto3->store('devoluciones/' . $this->asignacion->id, 'public');
            }

            // 2. Update Asignacion
            $this->asignacion->update([
                'fecha_devolucion' => $this->fecha_devolucion,
                'observaciones_devolucion' => "Estado al recibir: {$this->estado_recibido}.\nObservaciones: {$this->observaciones_devolucion}",
                'imagen_devolucion_1' => $paths['imagen_devolucion_1'],
                'imagen_devolucion_2' => $paths['imagen_devolucion_2'],
                'imagen_devolucion_3' => $paths['imagen_devolucion_3'],
                // estado_asignacion is implicit by date
            ]);

            // 3. Update Equipo Status
            $equipo = $this->asignacion->equipo;
            $equipo->update(['estado' => $this->estado_final_equipo]);

            // Registrar en historial
            \App\Services\HistorialService::registrar(
                $equipo->id,
                'DEVOLUCION',
                "Devuelto por empleado: {$this->asignacion->empleado->nombres} {$this->asignacion->empleado->apellidos}. Estado: {$this->estado_recibido}."
            );

            // 4. Create Reparacion if needed
            if ($this->estado_final_equipo === 'En Reparación') {
                Reparacion::create([
                    'id_equipo' => $equipo->id,
                    'fecha_ingreso' => Carbon::now(),
                    'motivo' => "Devuelto con estado '{$this->estado_recibido}'. Obs: {$this->observaciones_devolucion}",
                    'estado_reparacion' => 'En Proceso'
                ]);
            }
        });

        return redirect()->route('asignaciones.index')->with('status', 'Devolución registrada correctamente.');
    }

    #[Layout('layouts.app-legacy')]
    public function render()
    {
        return view('livewire.asignaciones.devolver');
    }
}
