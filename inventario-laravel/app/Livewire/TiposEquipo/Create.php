<?php

namespace App\Livewire\TiposEquipo;

use App\Models\TipoEquipo;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $estado = 'Activo';

    protected $rules = [
        'nombre' => 'required|string|max:100|unique:tipos_equipo,nombre',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        TipoEquipo::create([
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        return redirect()->route('tipos-equipo.index')->with('status', 'Tipo de equipo creado correctamente.');
    }

    public function render()
    {
        return view('livewire.tipos-equipo.create')->layout('layouts.app-legacy');
    }
}
