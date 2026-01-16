<?php

namespace App\Livewire\Cargos;

use App\Models\Cargo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $cargos = Cargo::with('area')->orderBy('nombre')->paginate(10);

        return view('livewire.cargos.index', [
            'cargos' => $cargos
        ])->layout('layouts.app-legacy');
    }
}
