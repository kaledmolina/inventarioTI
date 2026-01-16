<div>
    <h1 class="h2 mb-4">Dar de Baja Equipo</h1>

    <div class="card">
        <div class="card-header">
            Confirmar Baja para el Equipo: {{ $equipo->codigo_inventario }}
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <strong>¡Atención!</strong> Esta acción es irreversible. El estado del equipo cambiará a "De Baja" y no
                podrá ser asignado ni modificado.
            </div>

            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_baja" class="form-label">Fecha de Baja *</label>
                        <input type="date" class="form-control @error('fecha_baja') is-invalid @enderror"
                            wire:model="fecha_baja" required>
                        @error('fecha_baja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="motivo" class="form-label">Motivo de la Baja *</label>
                        <select class="form-select @error('motivo') is-invalid @enderror" wire:model.live="motivo"
                            required>
                            <option value="">Seleccione un motivo...</option>
                            <option value="Dañado sin reparación">Dañado sin reparación</option>
                            <option value="Obsoleto">Obsoleto</option>
                            <option value="Perdido / Robado">Perdido / Robado</option>
                            <option value="Otro">Otro</option>
                        </select>
                        @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control @error('observaciones') is-invalid @enderror"
                        wire:model.live="observaciones" rows="4"></textarea>
                    @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="acta_baja" class="form-label">Adjuntar Acta de Baja (Opcional)</label>
                    <input class="form-control @error('acta_baja') is-invalid @enderror" type="file"
                        wire:model="acta_baja" accept=".pdf, .jpg, .png">
                    <small class="form-text text-muted">Formatos permitidos: PDF, JPG, PNG. Max: 2MB.</small>
                    @error('acta_baja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>

                    @if($motivo)
                        <a href="{{ route('pdf.acta_baja', ['id_equipo' => $equipo->id, 'motivo' => $motivo, 'observaciones' => $observaciones]) }}"
                            target="_blank" class="btn btn-info">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Generar Acta
                        </a>
                    @else
                        <button type="button" class="btn btn-info" disabled title="Seleccione un motivo primero">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Generar Acta
                        </button>
                    @endif

                    <button type="submit" class="btn btn-danger">Confirmar Baja del Equipo</button>
                    <div wire:loading wire:target="save" class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>