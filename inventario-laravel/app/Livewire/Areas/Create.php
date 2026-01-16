<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $estado = 'Activo';

    protected $rules = [
        'nombre' => 'required|string|max:100|unique:areas,nombre',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        Area::create([
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        return redirect()->route('areas.index')->with('status', 'Área creada correctamente.');
    }

    public function render()
    {
        return view('livewire.areas.create')->layout('layouts.app-legacy');
    }
}
