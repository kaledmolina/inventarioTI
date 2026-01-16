<?php

namespace App\Livewire\Bajas;

use App\Models\Equipo;
use App\Models\Baja;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use WithFileUploads;

    public $id_equipo;
    public $equipo;
    public $fecha_baja;
    public $motivo;
    public $observaciones;
    public $acta_baja; // For file upload

    public function mount($id)
    {
        $this->id_equipo = $id;
        $this->equipo = Equipo::with(['marca', 'modelo'])->findOrFail($id);
        $this->fecha_baja = date('Y-m-d');

        if ($this->equipo->estado !== 'Disponible') {
            // Logic to handle if not available (e.g. in repair)
            // For now, assume button is only shown if available.
        }
    }

    protected $rules = [
        'fecha_baja' => 'required|date',
        'motivo' => 'required|string',
        'observaciones' => 'nullable|string',
        'acta_baja' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // 2MB max
    ];

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Upload File if present
            $actaPath = null;
            if ($this->acta_baja) {
                // Store in public/bajas (linked to storage/app/public/bajas)
                $actaPath = $this->acta_baja->store('bajas', 'public');
                // Store method returns "bajas/filename.ext". 
                // Legacy system stored just filename. We will store relative path compatible with Storage::url()
                // Let's store just the filename if we want to match legacy `asset('storage/bajas/' . $path)` or full relative path
                // Legacy view uses: echo $baja['acta_baja_path']
                // My view uses: asset('storage/bajas/' . $baja->acta_baja_path)
                // So I should store JUST the filename if I use that concatenation.
                // But store() returns "bajas/filename". 
                // Let's adjust my view to use asset('storage/' . $baja->acta_baja_path) instead.
            }

            // 2. Update Equipment Status
            $this->equipo->update(['estado' => 'De Baja']);

            // Registrar en historial
            \App\Services\HistorialService::registrar(
                $this->equipo->id,
                'BAJA',
                "Equipo dado de baja. Motivo: {$this->motivo}. Detalle: {$this->observaciones}"
            );

            // 3. Create Baja Record
            Baja::create([
                'id_equipo' => $this->id_equipo,
                'fecha_baja' => $this->fecha_baja,
                'motivo' => $this->motivo,
                'observaciones' => $this->observaciones,
                'acta_baja_path' => $actaPath, // e.g., "bajas/xyz.pdf"
            ]);
        });

        return redirect()->route('equipos.index')->with('status', 'Baja registrada correctamente.');
    }

    public function render()
    {
        return view('livewire.bajas.create')->layout('layouts.app-legacy');
    }
}
