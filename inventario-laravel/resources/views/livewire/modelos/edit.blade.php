<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Editar Modelo</h1>
        <a href="{{ route('modelos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="card col-md-8 mx-auto">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary fw-bold">Información del Modelo</h5>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre del Modelo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" wire:model="nombre">
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Marca <span class="text-danger">*</span></label>
                    <select class="form-select @error('id_marca') is-invalid @enderror" wire:model="id_marca">
                        <option value="">Seleccione marca...</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('modelos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar</button>
                    <div wire:loading wire:target="save" class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>