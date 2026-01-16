<?php

namespace App\Livewire\Asignaciones;

use App\Models\Asignacion;
use App\Models\Sucursal;
use App\Models\Empleado;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    // Legacy Filters
    public $sucursalId = '';
    public $empleadoId = '';
    public $equipoId = '';
    public $estado = '';
    public $fecha_desde = '';
    public $fecha_hasta = '';

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

        $asignaciones = Asignacion::query()
            ->with(['equipo.sucursal', 'equipo.marca', 'equipo.modelo', 'empleado'])
            // Filter by Sucursal (via Equipment relationship)
            ->when($this->sucursalId, function ($query) {
                $query->whereHas('equipo', function ($q) {
                    $q->where('id_sucursal', $this->sucursalId);
                });
            })
            // Enforce User Sucursal Restriction
            ->when($userSucursal, function ($query) use ($userSucursal) {
                $query->whereHas('equipo', function ($q) use ($userSucursal) {
                    $q->where('id_sucursal', $userSucursal);
                });
            })
            // Filter by Empleado
            ->when($this->empleadoId, function ($query) {
                $query->where('id_empleado', $this->empleadoId);
            })
            // Filter by Equipo
            ->when($this->equipoId, function ($query) {
                $query->where('id_equipo', $this->equipoId);
            })
            // Filter by Estado (Activa/Finalizada based on fecha_devolucion)
            ->when($this->estado, function ($query) {
                if ($this->estado === 'Activa') {
                    $query->whereNull('fecha_devolucion');
                } elseif ($this->estado === 'Finalizada') {
                    $query->whereNotNull('fecha_devolucion');
                }
            })
            // Filter by Date Range (fecha_entrega)
            ->when($this->fecha_desde, function ($query) {
                $query->whereDate('fecha_entrega', '>=', $this->fecha_desde);
            })
            ->when($this->fecha_hasta, function ($query) {
                $query->whereDate('fecha_entrega', '<=', $this->fecha_hasta);
            })
            // Order logic from legacy: estado ASC (Activa first), then Fecha Entrega DESC
            ->orderByRaw('CASE WHEN fecha_devolucion IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('fecha_entrega', 'desc')
            ->paginate($this->perPage);

        // Data for Filters
        // Sucursales: Only if Admin
        $sucursales = !$userSucursal ? Sucursal::where('estado', 'Activo')->orderBy('nombre')->get() : [];

        // Empleados: Filtered by user sucursal if applicable
        $empleados = Empleado::where('estado', 'Activo')
            ->when($userSucursal, fn($q) => $q->where('id_sucursal', $userSucursal))
            ->orderBy('apellidos')
            ->get();

        // Equipos: Filtered by user sucursal if applicable (Exclude De Baja)
        $equipos = Equipo::where('estado', '!=', 'De Baja')
            ->when($userSucursal, fn($q) => $q->where('id_sucursal', $userSucursal))
            ->orderBy('codigo_inventario')
            ->get();

        return view('livewire.asignaciones.index', [
            'asignaciones' => $asignaciones,
            'sucursales' => $sucursales,
            'empleados_list' => $empleados, // Renamed to avoid conflict with $asignaciones relation
            'equipos_list' => $equipos,
            'userSucursal' => $userSucursal
        ]);
    }
}
