<?php

namespace App\Livewire\Modelos;

use App\Models\Modelo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $modelos = Modelo::with('marca')->orderBy('nombre')->paginate(10);

        return view('livewire.modelos.index', [
            'modelos' => $modelos
        ])->layout('layouts.app-legacy');
    }
}
