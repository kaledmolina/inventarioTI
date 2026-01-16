<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Area;
use App\Models\Cargo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Filters
    public $sucursalId = '';
    public $estado = '';

    #[Layout('layouts.app-legacy')]
    public function render()
    {
        $empleados = Empleado::query()
            ->with(['sucursal', 'area', 'cargo'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombres', 'like', '%' . $this->search . '%')
                        ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                        ->orWhere('dni', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sucursalId, function ($query) {
                $query->where('id_sucursal', $this->sucursalId);
            })
            ->when($this->estado, function ($query) {
                $query->where('estado', $this->estado);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.empleados.index', [
            'empleados' => $empleados,
            'sucursales' => Sucursal::where('estado', 'Activo')->get(),
        ]);
    }
}
