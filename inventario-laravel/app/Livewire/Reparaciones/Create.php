<?php

namespace App\Livewire\Reparaciones;

use App\Models\Equipo;
use App\Models\Reparacion;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $id_equipo;
    public $equipo;
    public $fecha_ingreso;
    public $proveedor;
    public $motivo;

    public function mount($id)
    {
        $this->id_equipo = $id;
        $this->equipo = Equipo::with(['marca', 'modelo'])->findOrFail($id);
        $this->fecha_ingreso = date('Y-m-d');

        if ($this->equipo->estado !== 'Disponible' && $this->equipo->estado !== 'Asignado') {
            // Ideally we shouldn't be here if it's already in repair or retired
            // But strict check:
        }
    }

    protected $rules = [
        'fecha_ingreso' => 'required|date',
        'proveedor' => 'nullable|string|max:100',
        'motivo' => 'required|string',
    ];

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Update Equipment Status
            $this->equipo->update(['estado' => 'En Reparación']);

            // 2. Create Repair Record
            Reparacion::create([
                'id_equipo' => $this->id_equipo,
                'fecha_ingreso' => $this->fecha_ingreso,
                'motivo' => $this->motivo,
                'proveedor_servicio' => $this->proveedor,
                'estado_reparacion' => 'En Proceso'
            ]);
        });

        return redirect()->route('equipos.index')->with('status', 'Equipo enviado a reparación correctamente.');
    }

    public function render()
    {
        return view('livewire.reparaciones.create')->layout('layouts.app-legacy');
    }
}
