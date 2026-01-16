<?php

namespace App\Livewire\Marcas;

use App\Models\Marca;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $estado = 'Activo';

    protected $rules = [
        'nombre' => 'required|string|max:100|unique:marcas,nombre',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        Marca::create([
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        return redirect()->route('marcas.index')->with('status', 'Marca creada correctamente.');
    }

    public function render()
    {
        return view('livewire.marcas.create')->layout('layouts.app-legacy');
    }
}
