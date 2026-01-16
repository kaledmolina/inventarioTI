<?php

namespace App\Livewire\Reparaciones;

use App\Models\Reparacion;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filtroEstado = 'En Proceso';

    protected $paginationTheme = 'bootstrap';

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Reparacion::with(['equipo.marca', 'equipo.modelo'])
            ->orderBy('fecha_ingreso', 'desc');

        if ($this->filtroEstado !== 'Todas') {
            $query->where('estado_reparacion', $this->filtroEstado);
        }

        $reparaciones = $query->paginate(10);

        return view('livewire.reparaciones.index', [
            'reparaciones' => $reparaciones
        ])->layout('layouts.app-legacy');
    }
}
