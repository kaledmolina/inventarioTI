<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Sucursales</h2>
        <button class="btn btn-primary rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nueva Sucursal
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="form-control border-start-0 ps-0" placeholder="Buscar por nombre o dirección...">
                    </div>
                </div>
                <div class="col-md-2 ms-auto">
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">10 por pág.</option>
                        <option value="25">25 por pág.</option>
                        <option value="50">50 por pág.</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="border-0">Nombre</th>
                            <th scope="col" class="border-0">Dirección</th>
                            <th scope="col" class="border-0">Estado</th>
                            <th scope="col" class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sucursales as $sucursal)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold me-3"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($sucursal->nombre, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $sucursal->nombre }}</div>
                                            <div class="small text-muted">{{ $sucursal->telefono ?? 'Sin teléfono' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-secondary">{{ Str::limit($sucursal->direccion, 40) }}</td>
                                <td>
                                    @if($sucursal->estado === 'Activo')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Activo</span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-link text-decoration-none">Editar</button>
                                    <button class="btn btn-sm btn-link text-danger text-decoration-none">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                        No se encontraron sucursales.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $sucursales->links() }}
            </div>
        </div>
    </div>
</div>