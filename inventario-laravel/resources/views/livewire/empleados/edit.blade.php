<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Editar Empleado</h1>
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-person-lines-fill me-2"></i> Modificar Datos</h5>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Sucursal <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_sucursal') is-invalid @enderror" wire:model="id_sucursal"
                            required>
                            <option value="">Seleccione...</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_sucursal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">DNI <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('dni') is-invalid @enderror" wire:model="dni"
                            required>
                        @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombres') is-invalid @enderror"
                            wire:model="nombres" required>
                        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror"
                            wire:model="apellidos" required>
                        @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Área</label>
                        <select class="form-select @error('id_area') is-invalid @enderror" wire:model.live="id_area"
                            required>
                            <option value="">Seleccione...</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cargo</label>
                        <select class="form-select @error('id_cargo') is-invalid @enderror" wire:model="id_cargo"
                            @if(empty($cargos)) disabled @endif required>
                            <option value="">
                                @if(empty($this->id_area)) Seleccione un área primero
                                @elseif(empty($cargos)) No hay cargos disponibles
                                @else Seleccione... @endif
                            </option>
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_cargo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label>
                        <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                    <div wire:loading wire:target="save" class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>