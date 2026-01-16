<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Sucursal;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $empleadoId;
    public $id_sucursal;
    public $dni;
    public $nombres;
    public $apellidos;
    public $id_area;
    public $id_cargo;
    public $estado;

    public $sucursales;
    public $areas;
    public $cargos = [];

    public function mount($id)
    {
        $empleado = Empleado::findOrFail($id);
        $this->empleadoId = $empleado->id;
        $this->id_sucursal = $empleado->id_sucursal;
        $this->dni = $empleado->dni;
        $this->nombres = $empleado->nombres;
        $this->apellidos = $empleado->apellidos;
        $this->id_area = $empleado->id_area;
        $this->id_cargo = $empleado->id_cargo;
        $this->estado = $empleado->estado;

        $this->sucursales = Sucursal::where('estado', 'Activo')->orderBy('nombre')->get();
        $this->areas = Area::where('estado', 'Activo')->orderBy('nombre')->get();

        if ($this->id_area) {
            $this->cargos = Cargo::where('id_area', $this->id_area)->orderBy('nombre')->get();
        }
    }

    public function updatedIdArea($value)
    {
        if ($value) {
            $this->cargos = Cargo::where('id_area', $value)->orderBy('nombre')->get();
        } else {
            $this->cargos = [];
        }
        $this->id_cargo = ''; // Reset cargo when area changes
    }

    protected function rules()
    {
        return [
            'id_sucursal' => 'required|exists:sucursales,id',
            'dni' => ['required', 'string', 'max:20', Rule::unique('empleados')->ignore($this->empleadoId)],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'id_area' => 'required|exists:areas,id',
            'id_cargo' => 'required|exists:cargos,id',
            'estado' => 'required|in:Activo,Inactivo',
        ];
    }

    public function save()
    {
        $this->validate();

        $empleado = Empleado::findOrFail($this->empleadoId);
        $empleado->update([
            'id_sucursal' => $this->id_sucursal,
            'dni' => $this->dni,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'id_area' => $this->id_area,
            'id_cargo' => $this->id_cargo,
            'estado' => $this->estado,
        ]);

        return redirect()->route('empleados.index')->with('status', 'Empleado actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.empleados.edit')->layout('layouts.app-legacy');
    }
}
