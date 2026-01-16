<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Inventario de Equipos</h2>
        <button class="btn btn-primary rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Equipo
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="form-control border-start-0 ps-0" placeholder="Código, serie, marca...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="sucursalId" class="form-select">
                        <option value="">Sucursal...</option>
                        @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="tipoId" class="form-select">
                        <option value="">Tipo...</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="estado" class="form-select">
                        <option value="">Estado...</option>
                        <option value="Disponible">Disponible</option>
                        <option value="Asignado">Asignado</option>
                        <option value="En Reparacion">En Reparacion</option>
                        <option value="De Baja">De Baja</option>
                    </select>
                </div>
                <div class="col-md-2 ms-auto">
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
                            <th scope="col" class="border-0">Equipo / Código</th>
                            <th scope="col" class="border-0">Marca / Modelo</th>
                            <th scope="col" class="border-0">Ubicación</th>
                            <th scope="col" class="border-0">Estado</th>
                            <th scope="col" class="border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipos as $equipo)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded bg-light text-secondary d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            @if(str_contains(strtolower($equipo->tipo_equipo->nombre ?? ''), 'laptop'))
                                                <i class="bi bi-laptop fs-5"></i>
                                            @elseif(str_contains(strtolower($equipo->tipo_equipo->nombre ?? ''), 'celular'))
                                                <i class="bi bi-phone fs-5"></i>
                                            @else
                                                <i class="bi bi-pc-display fs-5"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $equipo->tipo_equipo->nombre ?? 'Desconocido' }}</div>
                                            <div class="small text-muted font-monospace">{{ $equipo->codigo_inventario }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $equipo->marca->nombre ?? '-' }}</div>
                                    <div class="small text-muted">{{ $equipo->modelo->nombre ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="text-secondary">{{ $equipo->sucursal->nombre ?? 'Sin Sucursal' }}</span>
                                </td>
                                <td>
                                    @php
                                        $colors = [
                                            'Disponible' => 'bg-success bg-opacity-10 text-success',
                                            'Asignado' => 'bg-primary bg-opacity-10 text-primary',
                                            'En Reparacion' => 'bg-warning bg-opacity-10 text-warning',
                                            'De Baja' => 'bg-secondary bg-opacity-10 text-secondary'
                                        ];
                                        $class = $colors[$equipo->estado] ?? 'bg-light text-dark';
                                    @endphp
                                    <span class="badge rounded-pill {{ $class }} px-3">
                                        {{ $equipo->estado }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-link text-decoration-none">Ver</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-50"></i>
                                        No se encontraron equipos.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $empleados->links() ?? '' }}
                <!-- Note: using equipos->links normally, var name depends on component -->
                {{ $equipos->links() }}
            </div>
        </div>
    </div>
</div>