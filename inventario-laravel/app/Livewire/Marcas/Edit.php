<?php

namespace App\Livewire\Marcas;

use App\Models\Marca;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $marcaId;
    public $nombre;
    public $estado;

    public function mount($id)
    {
        $marca = Marca::findOrFail($id);
        $this->marcaId = $marca->id;
        $this->nombre = $marca->nombre;
        $this->estado = $marca->estado;
    }

    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('marcas')->ignore($this->marcaId)],
            'estado' => 'required|in:Activo,Inactivo',
        ];
    }

    public function save()
    {
        $this->validate();

        $marca = Marca::findOrFail($this->marcaId);
        $marca->update([
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        return redirect()->route('marcas.index')->with('status', 'Marca actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.marcas.edit')->layout('layouts.app-legacy');
    }
}
