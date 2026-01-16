<?php

namespace App\Livewire\Equipos;

use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Sucursal;
use App\Models\TipoEquipo;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $equipoId;
    public $id_sucursal;
    public $codigo_inventario;
    public $numero_serie;
    public $id_tipo_equipo;
    public $id_marca;
    public $id_modelo;
    public $tipo_adquisicion;
    public $caracteristicas;
    public $fecha_adquisicion;
    public $proveedor;
    public $observaciones;
    public $estado; // Read-only for display

    public $sucursales;
    public $tipos;
    public $marcas;
    public $modelos = [];

    public function mount($id)
    {
        $equipo = Equipo::findOrFail($id);
        $this->equipoId = $equipo->id;
        $this->id_sucursal = $equipo->id_sucursal;
        $this->codigo_inventario = $equipo->codigo_inventario;
        $this->numero_serie = $equipo->numero_serie;
        $this->id_tipo_equipo = $equipo->id_tipo_equipo;
        $this->id_marca = $equipo->id_marca;
        $this->id_modelo = $equipo->id_modelo;
        $this->tipo_adquisicion = $equipo->tipo_adquisicion;
        $this->caracteristicas = $equipo->caracteristicas;
        $this->fecha_adquisicion = $equipo->fecha_adquisicion;
        $this->proveedor = $equipo->proveedor;
        $this->observaciones = $equipo->observaciones;
        $this->estado = $equipo->estado;

        $this->sucursales = Sucursal::where('estado', 'Activo')->orderBy('nombre')->get();
        $this->tipos = TipoEquipo::where('estado', 'Activo')->orderBy('nombre')->get();
        $this->marcas = Marca::where('estado', 'Activo')->orderBy('nombre')->get();

        if ($this->id_marca) {
            $this->modelos = Modelo::where('id_marca', $this->id_marca)->orderBy('nombre')->get();
        }
    }

    public function updatedIdMarca($value)
    {
        if ($value) {
            $this->modelos = Modelo::where('id_marca', $value)->orderBy('nombre')->get();
        } else {
            $this->modelos = [];
        }
        $this->id_modelo = '';
    }

    protected function rules()
    {
        return [
            'id_sucursal' => 'required|exists:sucursales,id',
            'codigo_inventario' => ['required', 'string', 'max:50', Rule::unique('equipos')->ignore($this->equipoId)],
            'id_tipo_equipo' => 'required|exists:tipos_equipo,id',
            'id_marca' => 'required|exists:marcas,id',
            'id_modelo' => 'nullable|exists:modelos,id',
            'numero_serie' => 'nullable|string|max:100',
            'tipo_adquisicion' => 'required|in:Propio,Alquilado,Leasing,Prestamo',
            'caracteristicas' => 'nullable|string',
            'fecha_adquisicion' => 'nullable|date',
            'proveedor' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        $equipo = Equipo::findOrFail($this->equipoId);
        $equipo->update([
            'id_sucursal' => $this->id_sucursal,
            'codigo_inventario' => $this->codigo_inventario,
            'numero_serie' => $this->numero_serie,
            'id_tipo_equipo' => $this->id_tipo_equipo,
            'id_marca' => $this->id_marca,
            'id_modelo' => $this->id_modelo ?: null,
            'tipo_adquisicion' => $this->tipo_adquisicion,
            'caracteristicas' => $this->caracteristicas,
            'fecha_adquisicion' => $this->fecha_adquisicion,
            'proveedor' => $this->proveedor,
            'observaciones' => $this->observaciones,
        ]);

        return redirect()->route('equipos.index')->with('status', 'Equipo actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.equipos.edit')->layout('layouts.app-legacy');
    }
}
