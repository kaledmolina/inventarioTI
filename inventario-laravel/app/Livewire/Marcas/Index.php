<?php

namespace App\Livewire\Marcas;

use App\Models\Marca;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $marcas = Marca::orderBy('nombre')->paginate(10);

        return view('livewire.marcas.index', [
            'marcas' => $marcas
        ])->layout('layouts.app-legacy');
    }
}
