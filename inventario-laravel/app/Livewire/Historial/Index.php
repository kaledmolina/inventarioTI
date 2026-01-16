<?php

namespace App\Livewire\Historial;

use App\Models\HistorialMovimiento;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $movimientos = HistorialMovimiento::with(['equipo', 'user'])
            ->where(function ($query) {
                $query->where('accion', 'like', '%' . $this->search . '%')
                    ->orWhereHas('equipo', function ($q) {
                        $q->where('codigo_inventario', 'like', '%' . $this->search . '%')
                            ->orWhere('numero_serie', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.historial.index', [
            'movimientos' => $movimientos
        ])->layout('layouts.app-legacy');
    }
}
