<?php

namespace App\Livewire\Bajas;

use App\Models\Baja;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $bajas = Baja::with(['equipo.marca', 'equipo.modelo'])
            ->orderBy('fecha_baja', 'desc')
            ->paginate(10);

        return view('livewire.bajas.index', [
            'bajas' => $bajas
        ])->layout('layouts.app-legacy');
    }
}
