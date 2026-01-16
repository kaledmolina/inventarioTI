<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Sucursal;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $id_sucursal = '';
    public $dni = '';
    public $nombres = '';
    public $apellidos = '';
    public $id_area = '';
    public $id_cargo = '';
    public $estado = 'Activo'; // Default

    public $sucursales;
    public $areas;
    public $cargos = [];

    public function mount()
    {
        $this->sucursales = Sucursal::where('estado', 'Activo')->orderBy('nombre')->get();
        $this->areas = Area::where('estado', 'Activo')->orderBy('nombre')->get();

        // Pre-fill sucursal if user has one
        if (Auth::user()->id_sucursal) {
            $this->id_sucursal = Auth::user()->id_sucursal;
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

    protected $rules = [
        'id_sucursal' => 'required|exists:sucursales,id',
        'dni' => 'required|string|max:20|unique:empleados,dni',
        'nombres' => 'required|string|max:100',
        'apellidos' => 'required|string|max:100',
        'id_area' => 'required|exists:areas,id',
        'id_cargo' => 'required|exists:cargos,id',
        // estado is defaulted to Activo and disabled in form
    ];

    public function save()
    {
        $this->validate();

        Empleado::create([
            'id_sucursal' => $this->id_sucursal,
            'dni' => $this->dni,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'id_area' => $this->id_area,
            'id_cargo' => $this->id_cargo,
            'estado' => $this->estado,
        ]);

        return redirect()->route('empleados.index')->with('status', 'Empleado registrado correctamente.');
    }

    public function render()
    {
        return view('livewire.empleados.create')->layout('layouts.app-legacy');
    }
}
