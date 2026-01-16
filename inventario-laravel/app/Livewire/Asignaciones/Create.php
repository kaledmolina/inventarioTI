<?php

namespace App\Livewire\Asignaciones;

use App\Models\Asignacion;
use App\Models\Equipo;
use App\Models\Empleado;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class Create extends Component
{
    public $sucursalId = '';
    public $empleadoId = '';
    public $equipoId = '';
    public $observaciones = '';

    public $empleados = [];
    public $equipos = [];

    public function mount()
    {
        // If user is restricted to a sucursal, set it and load data
        if (Auth::user()->id_sucursal) {
            $this->sucursalId = Auth::user()->id_sucursal;
            $this->updatedSucursalId($this->sucursalId);
        }
    }

    // Lifecycle hook: When sucursal changes, reload dependent dropdowns
    public function updatedSucursalId($value)
    {
        if ($value) {
            $this->empleados = Empleado::where('id_sucursal', $value)
                ->where('estado', 'Activo')
                ->orderBy('apellidos')
                ->get();

            $this->equipos = Equipo::where('id_sucursal', $value)
                ->where('estado', 'Disponible') // Only Available equipment
                ->orderBy('codigo_inventario')
                ->get();
        } else {
            $this->empleados = [];
            $this->equipos = [];
        }

        $this->empleadoId = '';
        $this->equipoId = '';
    }

    public function save()
    {
        $this->validate([
            'sucursalId' => 'required',
            'empleadoId' => 'required|exists:empleados,id',
            'equipoId' => 'required|exists:equipos,id',
            'observaciones' => 'nullable|string'
        ]);

        // Logic check: Verify equipment is still available (race condition)
        $equipo = Equipo::find($this->equipoId);
        if ($equipo->estado !== 'Disponible') {
            session()->flash('error', 'El equipo seleccionado ya no está disponible.');
            return;
        }

        // Create Assignment
        Asignacion::create([
            'id_equipo' => $this->equipoId,
            'id_empleado' => $this->empleadoId,
            'fecha_entrega' => Carbon::now(),
            'observaciones_entrega' => $this->observaciones, // Mapped column
            // 'estado_asignacion' is not a column in DB based on legacy logic (it's derived), 
            // but if we migrated specific columns we should check. 
            // Legacy query derived it: CASE WHEN fecha_devolucion IS NULL...
        ]);

        // Update Equipment Status
        $equipo->update(['estado' => 'Asignado']);

        return redirect()->route('asignaciones.index')->with('status', 'Asignación creada correctamente.');
    }

    #[Layout('layouts.app-legacy')]
    public function render()
    {
        // Admin gets all sucursales, User gets none (already handled in mount/template)
        $sucursales = !Auth::user()->id_sucursal ? Sucursal::where('estado', 'Activo')->orderBy('nombre')->get() : [];

        return view('livewire.asignaciones.create', [
            'sucursales' => $sucursales
        ]);
    }
}
