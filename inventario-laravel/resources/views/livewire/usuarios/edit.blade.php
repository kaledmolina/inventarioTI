<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Editar Usuario</h1>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="card col-md-8 mx-auto">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary fw-bold">Información del Usuario</h5>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nueva Contraseña <span class="text-muted fw-normal">(Dejar en
                            blanco para mantener la actual)</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        wire:model="password" placeholder="••••••••">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Roles <span class="text-danger">*</span></label>
                    <div class="card p-3 bg-light">
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $role->id }}"
                                    wire:model="selectedRoles" id="role_{{ $role->id }}">
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->nombre_rol }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedRoles') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Actualizar</button>
                    <div wire:loading wire:target="save" class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Guardando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>