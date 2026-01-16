<?php

namespace App\Livewire\Estados;

use App\Models\EstadoEquipo;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $descripcion;

    protected $rules = [
        'nombre' => 'required|string|max:50|unique:estados_equipo,nombre',
        'descripcion' => 'nullable|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        EstadoEquipo::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        return redirect()->route('estados.index')->with('status', 'Estado creado correctamente.');
    }

    public function render()
    {
        return view('livewire.estados.create')->layout('layouts.app-legacy');
    }
}
