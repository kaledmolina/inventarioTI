<?php

namespace App\Livewire\Sucursales;

use App\Models\Sucursal;
use Livewire\Component;

class Create extends Component
{
    public $nombre;
    public $direccion;
    public $telefono;
    public $email;
    public $estado = 'Activo';

    protected $rules = [
        'nombre' => 'required|string|max:100|unique:sucursales,nombre',
        'direccion' => 'nullable|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:100',
        'estado' => 'required|in:Activo,Inactivo',
    ];

    public function save()
    {
        $this->validate();

        Sucursal::create([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'estado' => $this->estado,
        ]);

        return redirect()->route('sucursales.index')->with('status', 'Sucursal creada correctamente.');
    }

    public function render()
    {
        return view('livewire.sucursales.create')->layout('layouts.app-legacy');
    }
}
