<div>
    <h1 class="h2 mb-4">Asignar Equipo a Empleado</h1>

    <div class="card">
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit="save">

                <div class="mb-3">
                    <label class="form-label">Seleccionar Sucursal *</label>
                    <select wire:model.live="sucursalId" class="form-select" {{ Auth::user()->id_sucursal ? 'disabled' : '' }}>
                        <option value="">Seleccione...</option>
                        @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                        @if(Auth::user()->id_sucursal)
                            <option value="{{ Auth::user()->id_sucursal }}">
                                {{ Auth::user()->sucursal->nombre ?? 'Mi Sucursal' }}</option>
                        @endif
                    </select>
                    @error('sucursalId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Seleccionar Empleado *</label>
                    <select wire:model="empleadoId" class="form-select" {{ empty($empleados) ? 'disabled' : '' }}>
                        <option value="">Seleccione...</option>
                        @foreach($empleados as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->apellidos }}, {{ $emp->nombres }} (DNI: {{ $emp->dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('empleadoId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Seleccionar Equipo *</label>
                    <select wire:model="equipoId" class="form-select" {{ empty($equipos) ? 'disabled' : '' }}>
                        <option value="">Seleccione...</option>
                        @foreach($equipos as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->codigo_inventario }} ({{ $eq->marca->nombre ?? '' }}
                                {{ $eq->modelo->nombre ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('equipoId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones de la Entrega</label>
                    <textarea wire:model="observaciones" class="form-control" rows="3"
                        placeholder="Ej: Se entrega con cargador y maletín."></textarea>
                </div>

                <hr>
                <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Asignar Equipo</button>
            </form>
        </div>
    </div>
</div>