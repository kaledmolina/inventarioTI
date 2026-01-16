<?php

namespace App\Livewire\Cargos;

use App\Models\Cargo;
use App\Models\Area;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $id_area;
    public $estado = 'Activo';

    public $areas;

    public function mount()
    {
        $this->areas = Area::where('estado', 'Activo')->orderBy('nombre')->get();
    }

    protected $rules = [
        'nombre' => 'required|string|max:100', // Unique check might need to be scoped to area, or global? Legacy likely global or just simplistic. Let's stick to simple unique if possible, but names can duplicate across areas? Typically yes. Let's just validate max len.
        'id_area' => 'required|exists:areas,id',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        Cargo::create([
            'nombre' => $this->nombre,
            'id_area' => $this->id_area,
            'estado' => $this->estado,
        ]);

        return redirect()->route('cargos.index')->with('status', 'Cargo creado correctamente.');
    }

    public function render()
    {
        return view('livewire.cargos.create')->layout('layouts.app-legacy');
    }
}
