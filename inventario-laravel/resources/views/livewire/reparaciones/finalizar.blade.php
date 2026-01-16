<div>
    <h1 class="h2 mb-4">Finalizar Reparación</h1>

    <div class="card">
        <div class="card-header">
            Registrar Retorno de Equipo
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5>Datos de la Reparación:</h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Equipo:</strong> {{ $reparacion->equipo->codigo_inventario }} -
                        {{ $reparacion->equipo->marca->nombre }} / {{ $reparacion->equipo->modelo->nombre }}</li>
                    <li class="list-group-item"><strong>Fecha de Ingreso:</strong>
                        {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}</li>
                    <li class="list-group-item"><strong>Proveedor/Técnico:</strong>
                        {{ $reparacion->proveedor_servicio }}</li>
                    <li class="list-group-item"><strong>Motivo:</strong> {{ $reparacion->motivo }}</li>
                </ul>
            </div>

            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_salida" class="form-label">Fecha de Finalización/Retorno *</label>
                        <input type="date" class="form-control @error('fecha_salida') is-invalid @enderror"
                            wire:model="fecha_salida" required>
                        @error('fecha_salida') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i> Al finalizar, el estado del equipo cambiará automáticamente a
                    <strong>"Disponible"</strong>.
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('reparaciones.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Confirmar Finalización</button>
                    <div wire:loading wire:target="save" class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>