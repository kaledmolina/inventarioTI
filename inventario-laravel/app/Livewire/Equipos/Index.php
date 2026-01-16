<?php

namespace App\Livewire\Equipos;

use App\Models\Equipo;
use App\Models\Sucursal;
use App\Models\TipoEquipo;
use App\Models\Marca;
use App\Models\Modelo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Filters
    public $sucursalId = '';
    public $tipoId = '';
    public $estado = '';

    #[Layout('layouts.app-legacy')]
    public function render()
    {
        $equipos = Equipo::query()
            ->with(['sucursal', 'tipo_equipo', 'marca', 'modelo'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('codigo_inventario', 'like', '%' . $this->search . '%')
                        ->orWhere('numero_serie', 'like', '%' . $this->search . '%')
                        ->orWhereHas('marca', fn($q2) => $q2->where('nombre', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('modelo', fn($q2) => $q2->where('nombre', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->sucursalId, function ($query) {
                $query->where('id_sucursal', $this->sucursalId);
            })
            ->when($this->tipoId, function ($query) {
                $query->where('id_tipo_equipo', $this->tipoId);
            })
            ->when($this->estado, function ($query) {
                $query->where('estado', $this->estado);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.equipos.index', [
            'equipos' => $equipos,
            'sucursales' => Sucursal::where('estado', 'Activo')->get(),
            'tipos' => TipoEquipo::where('estado', 'Activo')->get(),
        ]);
    }
}
