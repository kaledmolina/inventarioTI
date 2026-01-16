<?php

namespace App\Livewire\Equipos;

use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Sucursal;
use App\Models\TipoEquipo;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $id_sucursal = '';
    public $codigo_inventario = '';
    public $numero_serie = '';
    public $id_tipo_equipo = '';
    public $id_marca = '';
    public $id_modelo = '';
    public $tipo_adquisicion = 'Propio';
    public $caracteristicas = '';
    public $fecha_adquisicion = '';
    public $proveedor = '';
    public $observaciones = '';

    public $sucursales;
    public $tipos;
    public $marcas;
    public $modelos = [];

    public function mount()
    {
        $this->sucursales = Sucursal::where('estado', 'Activo')->orderBy('nombre')->get();
        $this->tipos = TipoEquipo::where('estado', 'Activo')->orderBy('nombre')->get();
        $this->marcas = Marca::where('estado', 'Activo')->orderBy('nombre')->get();

        // Set default date to today
        $this->fecha_adquisicion = date('Y-m-d');

        // Pre-fill sucursal if user has one
        if (Auth::user()->id_sucursal) {
            $this->id_sucursal = Auth::user()->id_sucursal;
        }
    }

    public function updatedIdMarca($value)
    {
        if ($value) {
            $this->modelos = Modelo::where('id_marca', $value)->orderBy('nombre')->get();
        } else {
            $this->modelos = [];
        }
        $this->id_modelo = ''; // Reset modelo when marca changes
    }

    protected $rules = [
        'id_sucursal' => 'required|exists:sucursales,id',
        'codigo_inventario' => 'required|string|max:50|unique:equipos,codigo_inventario',
        'id_tipo_equipo' => 'required|exists:tipos_equipo,id',
        'id_marca' => 'required|exists:marcas,id',
        'id_modelo' => 'nullable|exists:modelos,id', // Can be nullable if not selected or no models exist? Legacy logic doesn't strictly force it but good practice. Let's make it nullable but ideal if selected.
        'numero_serie' => 'nullable|string|max:100',
        'tipo_adquisicion' => 'required|in:Propio,Alquilado,Leasing,Prestamo',
        'caracteristicas' => 'nullable|string',
        'fecha_adquisicion' => 'nullable|date',
        'proveedor' => 'nullable|string|max:100',
        'observaciones' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        Equipo::create([
            'id_sucursal' => $this->id_sucursal,
            'codigo_inventario' => $this->codigo_inventario,
            'numero_serie' => $this->numero_serie,
            'id_tipo_equipo' => $this->id_tipo_equipo,
            'id_marca' => $this->id_marca,
            'id_modelo' => $this->id_modelo ?: null, // Store null if empty
            'tipo_adquisicion' => $this->tipo_adquisicion,
            'caracteristicas' => $this->caracteristicas,
            'fecha_adquisicion' => $this->fecha_adquisicion,
            'proveedor' => $this->proveedor,
            'observaciones' => $this->observaciones,
            'estado' => 'Disponible', // Default state
        ]);

        return redirect()->route('equipos.index')->with('status', 'Equipo registrado correctamente.');
    }

    public function render()
    {
        return view('livewire.equipos.create')->layout('layouts.app-legacy');
    }
}
