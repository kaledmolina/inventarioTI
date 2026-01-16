<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Area;
use App\Models\Cargo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    // Legacy Filters
    public $texto = ''; // Matches 'texto' filter for DNI/Names
    public $sucursalId = '';
    public $areaId = '';
    public $cargoId = '';
    public $estado = '';

    public $perPage = 10;

    public function mount()
    {
        if (Auth::user()->id_sucursal) {
            $this->sucursalId = Auth::user()->id_sucursal;
        }
    }

    #[Layout('layouts.app-legacy')]
    public function render()
    {
        $userSucursal = Auth::user()->id_sucursal;

        $empleados = Empleado::query()
            ->with(['sucursal', 'area', 'cargo'])
            ->when($this->texto, function ($query) {
                $query->where(function ($q) {
                    $q->where('dni', 'like', '%' . $this->texto . '%')
                        ->orWhere('nombres', 'like', '%' . $this->texto . '%')
                        ->orWhere('apellidos', 'like', '%' . $this->texto . '%');
                });
            })
            ->when($this->sucursalId, function ($query) {
                $query->where('id_sucursal', $this->sucursalId);
            })
            ->when($userSucursal, function ($query) use ($userSucursal) {
                $query->where('id_sucursal', $userSucursal);
            })
            ->when($this->areaId, function ($query) {
                $query->where('id_area', $this->areaId);
            })
            ->when($this->cargoId, function ($query) {
                $query->where('id_cargo', $this->cargoId);
            })
            ->when($this->estado, function ($query) {
                $query->where('estado', $this->estado);
            })
            ->orderBy('apellidos', 'asc') // Legacy logic sort by Apellidos then Nombres
            ->orderBy('nombres', 'asc')
            ->paginate($this->perPage);

        return view('livewire.empleados.index', [
            'empleados' => $empleados,
            'sucursales' => !$userSucursal ? Sucursal::where('estado', 'Activo')->orderBy('nombre')->get() : [],
            'areas' => Area::where('estado', 'Activo')->orderBy('nombre')->get(),
            'cargos' => Cargo::where('estado', 'Activo')->orderBy('nombre')->get(),
            'userSucursal' => $userSucursal
        ]);
    }
}
