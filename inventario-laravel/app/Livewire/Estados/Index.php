<?php

namespace App\Livewire\Estados;

use App\Models\EstadoEquipo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $estados = EstadoEquipo::orderBy('nombre')->paginate(10);

        return view('livewire.estados.index', [
            'estados' => $estados
        ])->layout('layouts.app-legacy');
    }
}
