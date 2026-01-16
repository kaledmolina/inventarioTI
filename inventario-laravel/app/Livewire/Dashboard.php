<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Equipo;
use App\Models\Sucursal;
use App\Models\Empleado;
use App\Models\TipoEquipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalEquipos = 0;
    public $equiposAsignados = 0;
    public $equiposDisponibles = 0;

    public $tiposLabels = [];
    public $tiposData = [];

    public $sucursalLabels = [];
    public $dataEquipos = [];
    public $dataEmpleados = [];

    public function mount()
    {
        $user = Auth::user();
        $sucursalId = $user->id_sucursal; // Assuming this field exists and is populated

        // Base Query builders
        $equiposQuery = Equipo::query();

        // Filter by Sucursal if user has one assigned (and not admin/super-user logic? Legacy implies strict id_sucursal filter)
        // In legacy: if ($id_sucursal_usuario !== null) filter.
        if ($sucursalId) {
            $equiposQuery->where('id_sucursal', $sucursalId);
        }

        // KPIs
        $this->totalEquipos = (clone $equiposQuery)->count();
        $this->equiposAsignados = (clone $equiposQuery)->where('estado', 'Asignado')->count();
        $this->equiposDisponibles = (clone $equiposQuery)->where('estado', 'Disponible')->count();

        // Chart 1: Equipos Disponibles por Tipo
        $chart1Query = DB::table('equipos')
            ->join('tipos_equipo', 'equipos.id_tipo_equipo', '=', 'tipos_equipo.id')
            ->select('tipos_equipo.nombre', DB::raw('count(equipos.id) as cantidad'));

        if ($sucursalId) {
            $chart1Query->where('equipos.id_sucursal', $sucursalId);
        }

        $chart1Data = $chart1Query->groupBy('tipos_equipo.nombre')->get();

        foreach ($chart1Data as $row) {
            $this->tiposLabels[] = $row->nombre;
            $this->tiposData[] = $row->cantidad;
        }

        // Chart 2: Equipos y Empleados por Sucursal
        // Legacy: SELECT s.nombre, count(equipos), count(empleados) FROM sucursales ...

        $sucursalesQuery = Sucursal::where('estado', 'Activo');
        if ($sucursalId) {
            $sucursalesQuery->where('id', $sucursalId);
        }

        $sucursales = $sucursalesQuery->get();

        foreach ($sucursales as $sucursal) {
            $this->sucursalLabels[] = $sucursal->nombre;
            $this->dataEquipos[] = Equipo::where('id_sucursal', $sucursal->id)->count();
            $this->dataEmpleados[] = Empleado::where('id_sucursal', $sucursal->id)->where('estado', 'Activo')->count();
        }
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app-legacy');
    }
}
