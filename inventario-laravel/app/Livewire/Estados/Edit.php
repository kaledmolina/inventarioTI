<?php

namespace App\Livewire\Estados;

use App\Models\EstadoEquipo;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $estadoId;
    public $nombre;
    public $descripcion;

    public function mount($id)
    {
        $estado = EstadoEquipo::findOrFail($id);
        $this->estadoId = $estado->id;
        $this->nombre = $estado->nombre;
        $this->descripcion = $estado->descripcion;
    }

    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:50', Rule::unique('estados_equipo')->ignore($this->estadoId)],
            'descripcion' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        $estado = EstadoEquipo::findOrFail($this->estadoId);
        $estado->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        return redirect()->route('estados.index')->with('status', 'Estado actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.estados.edit')->layout('layouts.app-legacy');
    }
}
