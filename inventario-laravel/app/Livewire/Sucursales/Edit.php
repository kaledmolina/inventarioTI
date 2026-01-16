<?php

namespace App\Livewire\Sucursales;

use App\Models\Sucursal;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $sucursalId;
    public $nombre;
    public $direccion;
    public $telefono;
    public $email;
    public $estado;

    public function mount($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $this->sucursalId = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        $this->email = $sucursal->email;
        $this->estado = $sucursal->estado;
    }

    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:100', Rule::unique('sucursales')->ignore($this->sucursalId)],
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'estado' => 'required|in:Activo,Inactivo',
        ];
    }

    public function save()
    {
        $this->validate();

        $sucursal = Sucursal::findOrFail($this->sucursalId);
        $sucursal->update([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'estado' => $this->estado,
        ]);

        return redirect()->route('sucursales.index')->with('status', 'Sucursal actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.sucursales.edit')->layout('layouts.app-legacy');
    }
}
