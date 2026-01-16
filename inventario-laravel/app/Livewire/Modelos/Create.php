<?php

namespace App\Livewire\Modelos;

use App\Models\Modelo;
use App\Models\Marca;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $id_marca;
    public $estado = 'Activo';

    public $marcas;

    public function mount()
    {
        $this->marcas = Marca::where('estado', 'Activo')->orderBy('nombre')->get();
    }

    protected $rules = [
        'nombre' => 'required|string|max:100', // Unique check per marca? Simple validation string for now.
        'id_marca' => 'required|exists:marcas,id',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        Modelo::create([
            'nombre' => $this->nombre,
            'id_marca' => $this->id_marca,
            'estado' => $this->estado,
        ]);

        return redirect()->route('modelos.index')->with('status', 'Modelo creado correctamente.');
    }

    public function render()
    {
        return view('livewire.modelos.create')->layout('layouts.app-legacy');
    }
}
