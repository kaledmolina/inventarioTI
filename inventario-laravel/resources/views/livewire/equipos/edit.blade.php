<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Editar Equipo</h1>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
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
            <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-pencil-square me-2"></i> Modificar Información</h5>
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
                        <label class="form-label fw-bold">Código Inventario <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('codigo_inventario') is-invalid @enderror"
                            wire:model="codigo_inventario" required>
                        @error('codigo_inventario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Número de Serie</label>
                        <input type="text" class="form-control @error('numero_serie') is-invalid @enderror"
                            wire:model="numero_serie">
                        @error('numero_serie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estado del Equipo <span class="text-danger">*</span></label>
                        <select class="form-select bg-light" disabled>
                            <option>{{ $estado }} (No se puede cambiar aquí)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_tipo_equipo') is-invalid @enderror"
                            wire:model="id_tipo_equipo" required>
                            <option value="">Seleccione...</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_tipo_equipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Marca <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_marca') is-invalid @enderror" wire:model.live="id_marca"
                            required>
                            <option value="">Seleccione...</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Modelo</label>
                        <select class="form-select @error('id_modelo') is-invalid @enderror" wire:model="id_modelo"
                            @if(empty($modelos)) disabled @endif>
                            <option value="">
                                @if(empty($this->id_marca)) Seleccione una marca primero
                                @elseif(empty($modelos)) No hay modelos disponibles
                                @else Seleccione... @endif
                            </option>
                            @foreach($modelos as $modelo)
                                <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipo de Adquisición</label>
                        <select class="form-select @error('tipo_adquisicion') is-invalid @enderror"
                            wire:model="tipo_adquisicion">
                            <option value="Propio">Propio</option>
                            <option value="Alquilado">Alquilado</option>
                            <option value="Leasing">Leasing</option>
                            <option value="Prestamo">Préstamo</option>
                        </select>
                        @error('tipo_adquisicion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Características</label>
                        <input type="text" class="form-control @error('caracteristicas') is-invalid @enderror"
                            wire:model="caracteristicas">
                        @error('caracteristicas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Adquisición</label>
                        <input type="date" class="form-control @error('fecha_adquisicion') is-invalid @enderror"
                            wire:model="fecha_adquisicion">
                        @error('fecha_adquisicion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Proveedor</label>
                        <input type="text" class="form-control @error('proveedor') is-invalid @enderror"
                            wire:model="proveedor">
                        @error('proveedor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Observaciones</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror"
                            wire:model="observaciones" rows="3"></textarea>
                        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                    <div wire:loading wire:target="save" class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>