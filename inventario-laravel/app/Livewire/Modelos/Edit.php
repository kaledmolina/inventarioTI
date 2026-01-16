<?php

namespace App\Livewire\Modelos;

use App\Models\Modelo;
use App\Models\Marca;
use Livewire\Component;

class Edit extends Component
{
    public $modeloId;
    public $nombre;
    public $id_marca;
    public $estado;

    public $marcas;

    public function mount($id)
    {
        $modelo = Modelo::findOrFail($id);
        $this->modeloId = $modelo->id;
        $this->nombre = $modelo->nombre;
        $this->id_marca = $modelo->id_marca;
        $this->estado = $modelo->estado;

        $this->marcas = Marca::where('estado', 'Activo')->orderBy('nombre')->get();
    }

    protected $rules = [
        'nombre' => 'required|string|max:100',
        'id_marca' => 'required|exists:marcas,id',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        $modelo = Modelo::findOrFail($this->modeloId);
        $modelo->update([
            'nombre' => $this->nombre,
            'id_marca' => $this->id_marca,
            'estado' => $this->estado,
        ]);

        return redirect()->route('modelos.index')->with('status', 'Modelo actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.modelos.edit')->layout('layouts.app-legacy');
    }
}
