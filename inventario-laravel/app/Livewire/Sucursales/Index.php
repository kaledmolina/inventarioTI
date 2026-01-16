<?php

namespace App\Livewire\Sucursales;

use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function render()
    {
        $sucursales = Sucursal::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.sucursales.index', [
            'sucursales' => $sucursales
        ])->layout('layouts.app-legacy');
    }
}
