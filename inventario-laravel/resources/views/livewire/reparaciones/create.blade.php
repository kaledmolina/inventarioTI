<div>
    <h1 class="h2 mb-4">Enviar Equipo a Reparación</h1>

    <div class="card">
        <div class="card-header">
            Registrar Inicio de Proceso de Reparación
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5>Equipo a reparar:</h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Código:</strong> {{ $equipo->codigo_inventario }}</li>
                    <li class="list-group-item"><strong>Marca/Modelo:</strong> {{ $equipo->marca->nombre }} /
                        {{ $equipo->modelo->nombre }}</li>
                </ul>
            </div>

            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Envío *</label>
                        <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror"
                            wire:model="fecha_ingreso" required>
                        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="proveedor" class="form-label">Proveedor de Servicio / Técnico</label>
                        <input type="text" class="form-control @error('proveedor') is-invalid @enderror"
                            wire:model="proveedor">
                        @error('proveedor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo de la Falla / Reparación *</label>
                    <textarea class="form-control @error('motivo') is-invalid @enderror" wire:model="motivo" rows="4"
                        required></textarea>
                    @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar y Enviar a Reparación</button>
                    <div wire:loading wire:target="save" class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>