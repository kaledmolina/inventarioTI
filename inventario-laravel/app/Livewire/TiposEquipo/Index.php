<?php

namespace App\Livewire\TiposEquipo;

use App\Models\TipoEquipo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $tipos = TipoEquipo::orderBy('nombre')->paginate(10);

        return view('livewire.tipos-equipo.index', [
            'tipos' => $tipos
        ])->layout('layouts.app-legacy');
    }
}
