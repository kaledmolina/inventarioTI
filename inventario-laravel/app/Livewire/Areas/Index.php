<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $areas = Area::orderBy('nombre')->paginate(10);

        return view('livewire.areas.index', [
            'areas' => $areas
        ])->layout('layouts.app-legacy');
    }
}
