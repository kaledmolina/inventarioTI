<?php

namespace App\Livewire\TiposEquipo;

use App\Models\TipoEquipo;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $tipoId;
    public $nombre;
    public $estado;

    public function mount($id)
    {
        $tipo = TipoEquipo::findOrFail($id);
        $this->tipoId = $tipo->id;
        $this->nombre = $tipo->nombre;
        $this->estado = $tipo->estado;
    }

    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('tipos_equipo')->ignore($this->tipoId)],
            'estado' => 'required|in:Activo,Inactivo',
        ];
    }

    public function save()
    {
        $this->validate();

        $tipo = TipoEquipo::findOrFail($this->tipoId);
        $tipo->update([
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        return redirect()->route('tipos-equipo.index')->with('status', 'Tipo actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.tipos-equipo.edit')->layout('layouts.app-legacy');
    }
}
