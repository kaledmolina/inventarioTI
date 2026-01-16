<?php

namespace App\Livewire\Reparaciones;

use App\Models\Reparacion;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Finalizar extends Component
{
    public $id_reparacion;
    public $reparacion;
    public $fecha_salida;

    public function mount($id)
    {
        $this->id_reparacion = $id;
        $this->reparacion = Reparacion::with('equipo')->findOrFail($id);
        $this->fecha_salida = date('Y-m-d');

        if ($this->reparacion->estado_reparacion !== 'En Proceso') {
            // Already finalized
            return redirect()->route('reparaciones.index');
        }
    }

    protected $rules = [
        'fecha_salida' => 'required|date|after_or_equal:reparacion.fecha_ingreso',
    ];

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Update Repair Record
            $this->reparacion->update([
                'fecha_salida' => $this->fecha_salida,
                'estado_reparacion' => 'Finalizada'
            ]);

            // 2. Update Equipment Status
            $this->reparacion->equipo->update(['estado' => 'Disponible']);
        });

        return redirect()->route('reparaciones.index')->with('status', 'Reparación finalizada y equipo disponible.');
    }

    public function render()
    {
        return view('livewire.reparaciones.finalizar')->layout('layouts.app-legacy');
    }
}
