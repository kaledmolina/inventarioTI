<?php

namespace App\Livewire\Equipos;

use App\Models\Equipo;
use App\Models\Sucursal;
use App\Models\TipoEquipo;
use App\Models\Marca;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    // Legacy Filters
    public $codigo_inventario = '';
    public $numero_serie = '';
    public $sucursalId = '';
    public $tipoId = '';
    public $marcaId = '';
    public $estado = '';

    public $perPage = 10;

    // Maintain strict fidelity with legacy 'user_sucursal_id' logic
    public function mount()
    {
        // If user is assigned to a sucursal, force filter and disable modification
        if (Auth::user()->id_sucursal) {
            $this->sucursalId = Auth::user()->id_sucursal;
        }
    }

    #[Layout('layouts.app-legacy')]
    public function render()
    {
        $userSucursal = Auth::user()->id_sucursal;

        $equipos = Equipo::query()
            ->with(['sucursal', 'tipo_equipo', 'marca', 'modelo'])
            // Apply Legacy Filters Matches
            ->when($this->codigo_inventario, function ($query) {
                $query->where('codigo_inventario', 'like', '%' . $this->codigo_inventario . '%');
            })
            ->when($this->numero_serie, function ($query) {
                $query->where('numero_serie', 'like', '%' . $this->numero_serie . '%');
            })
            ->when($this->sucursalId, function ($query) {
                $query->where('id_sucursal', $this->sucursalId);
            })
            // Enforce User Sucursal Restriction if exists (Legacy fidelity)
            ->when($userSucursal, function ($query) use ($userSucursal) {
                $query->where('id_sucursal', $userSucursal);
            })
            ->when($this->tipoId, function ($query) {
                $query->where('id_tipo_equipo', $this->tipoId);
            })
            ->when($this->marcaId, function ($query) {
                $query->where('id_marca', $this->marcaId);
            })
            ->when($this->estado, function ($query) {
                $query->where('estado', $this->estado);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.equipos.index', [
            'equipos' => $equipos,
            // Show all sucursales only if user is admin (no sucursal assigned) matches legacy logic check
            'sucursales' => !$userSucursal ? Sucursal::where('estado', 'Activo')->orderBy('nombre')->get() : [],
            'tipos' => TipoEquipo::orderBy('nombre')->get(),
            'marcas' => Marca::orderBy('nombre')->get(),
            'userSucursal' => $userSucursal
        ]);
    }
}
