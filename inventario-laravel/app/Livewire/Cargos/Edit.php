<?php

namespace App\Livewire\Cargos;

use App\Models\Cargo;
use App\Models\Area;
use Livewire\Component;

class Edit extends Component
{
    public $cargoId;
    public $nombre;
    public $id_area;
    public $estado;

    public $areas;

    public function mount($id)
    {
        $cargo = Cargo::findOrFail($id);
        $this->cargoId = $cargo->id;
        $this->nombre = $cargo->nombre;
        $this->id_area = $cargo->id_area;
        $this->estado = $cargo->estado;

        $this->areas = Area::where('estado', 'Activo')->orderBy('nombre')->get();
    }

    protected $rules = [
        'nombre' => 'required|string|max:100',
        'id_area' => 'required|exists:areas,id',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        $cargo = Cargo::findOrFail($this->cargoId);
        $cargo->update([
            'nombre' => $this->nombre,
            'id_area' => $this->id_area,
            'estado' => $this->estado,
        ]);

        return redirect()->route('cargos.index')->with('status', 'Cargo actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.cargos.edit')->layout('layouts.app-legacy');
    }
}
