<div>
    <h1 class="h2 mb-4">Registrar Devolución de Equipo</h1>

    <div class="card">
        <div class="card-header">
            Confirmar Devolución
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Empleado:</dt>
                <dd class="col-sm-9">{{ $asignacion->empleado->apellidos }}, {{ $asignacion->empleado->nombres }}</dd>
                <dt class="col-sm-3">Equipo:</dt>
                <dd class="col-sm-9">{{ $asignacion->equipo->codigo_inventario }}
                    ({{ $asignacion->equipo->marca->nombre ?? '' }} {{ $asignacion->equipo->modelo->nombre ?? '' }})
                </dd>
            </dl>
            <hr>

            <form wire:submit="save">

                <div class="mb-3">
                    <label class="form-label">Fecha de Devolución *</label>
                    <input wire:model="fecha_devolucion" type="datetime-local" class="form-control" required>
                    @error('fecha_devolucion') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado en que se recibe el equipo *</label>
                    <select wire:model="estado_recibido" class="form-select" required>
                        <option value="Bueno">Bueno</option>
                        <option value="Regular (con detalles)">Regular (con detalles)</option>
                        <option value="Dañado">Dañado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones Adicionales *</label>
                    <textarea wire:model="observaciones_devolucion" class="form-control" rows="3"
                        placeholder="Ej: el equipo no enciende, presenta rayones en la tapa, etc." required></textarea>
                    @error('observaciones_devolucion') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado final del equipo en inventario *</label>
                    <select wire:model="estado_final_equipo" class="form-select" required>
                        <option value="Disponible">Disponible (para reasignar)</option>
                        <option value="En Reparación">En Reparación (Enviar a módulo de reparaciones)</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Evidencia Fotográfica 1 (Opcional)</label>
                        <input wire:model="foto1" class="form-control" type="file" accept="image/*">
                        @error('foto1') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Evidencia Fotográfica 2 (Opcional)</label>
                        <input wire:model="foto2" class="form-control" type="file" accept="image/*">
                        @error('foto2') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Evidencia Fotográfica 3 (Opcional)</label>
                        <input wire:model="foto3" class="form-control" type="file" accept="image/*">
                        @error('foto3') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <span wire:loading.remove>Confirmar Devolución</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>