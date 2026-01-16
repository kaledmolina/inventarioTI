<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Colaboradores</h2>
        <button class="btn btn-primary rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Empleado
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="form-control border-start-0 ps-0" placeholder="Buscar por nombre, DNI...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="sucursalId" class="form-select">
                        <option value="">Todas las Sucursales</option>
                        @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="estado" class="form-select">
                        <option value="">Todos los Estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="border-0">Empleado</th>
                            <th scope="col" class="border-0">Cargo / Área</th>
                            <th scope="col" class="border-0">Sucursal</th>
                            <th scope="col" class="border-0">Estado</th>
                            <th scope="col" class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empleados as $empleado)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold me-3"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($empleado->nombres, 0, 1) }}{{ substr($empleado->apellidos, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $empleado->nombres }}
                                                {{ $empleado->apellidos }}</div>
                                            <div class="small text-muted">DNI: {{ $empleado->dni }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $empleado->cargo->nombre ?? 'N/A' }}</div>
                                    <div class="small text-muted">{{ $empleado->area->nombre ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="text-secondary"><i
                                            class="bi bi-geo-alt me-1"></i>{{ $empleado->sucursal->nombre ?? 'Sin Asignar' }}</span>
                                </td>
                                <td>
                                    @if($empleado->estado === 'Activo')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Activo</span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-link text-decoration-none">Editar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-people fs-1 d-block mb-3 opacity-50"></i>
                                        No se encontraron colaboradores.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $empleados->links() }}
            </div>
        </div>
    </div>
</div>