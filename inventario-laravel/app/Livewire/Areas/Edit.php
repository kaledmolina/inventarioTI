<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $areaId;
    public $nombre;
    public $estado;

    public function mount($id)
    {
        $area = Area::findOrFail($id);
        $this->areaId = $area->id;
        $this->nombre = $area->nombre;
        $this->estado = $area->estado;
    }

    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('areas')->ignore($this->areaId)],
            'estado' => 'required|in:Activo,Inactivo',
        ];
    }

    public function save()
    {
        $this->validate();

        $area = Area::findOrFail($this->areaId);
        $area->update([
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        return redirect()->route('areas.index')->with('status', 'Área actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.areas.edit')->layout('layouts.app-legacy');
    }
}
