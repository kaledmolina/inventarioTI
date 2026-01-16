<div>
    <h1 class="h2 mb-3">Gestión de Empleados</h1>

    <div class="card mb-4">
        <div class="card-header"><i class="bi bi-funnel-fill"></i> Filtros y Reportes</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar por DNI o Nombre</label>
                    <input wire:model.live.debounce.300ms="texto" type="text" class="form-control form-control-sm">
                </div>
                
                @if(!$userSucursal)
                <div class="col-md-4">
                    <label class="form-label">Sucursal</label>
                    <select wire:model.live="sucursalId" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-md-4">
                    <label class="form-label">Área</label>
                    <select wire:model.live="areaId" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                 <div class="col-md-4">
                    <label class="form-label">Cargo</label>
                    <select wire:model.live="cargoId" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select wire:model.live="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                     <button wire:click="$set('texto', '')" class="btn btn-secondary btn-sm">Limpiar</button>
                </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
                <button class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                <button class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
                <button class="btn btn-info"><i class="bi bi-printer"></i> Imprimir</button>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4">Listado de Empleados</h2>
        <button class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Empleado</button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            @if(!$userSucursal) <th>Sucursal</th> @endif
                            <th>DNI</th>
                            <th>Apellidos y Nombres</th>
                            <th>Cargo</th>
                            <th>Área</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empleados as $empleado)
                            <tr>
                                @if(!$userSucursal) <td>{{ $empleado->sucursal->nombre ?? 'N/A' }}</td> @endif
                                <td>{{ $empleado->dni }}</td>
                                <td>{{ $empleado->apellidos }}, {{ $empleado->nombres }}</td>
                                <td>{{ $empleado->cargo->nombre ?? 'N/A' }}</td>
                                <td>{{ $empleado->area->nombre ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $empleado->estado === 'Activo' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $empleado->estado }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ !$userSucursal ? 7 : 6 }}" class="text-center py-5">
                                    <div class="text-muted">No se encontraron empleados.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-4">
                {{ $empleados->links() }}
            </div>
        </div>
    </div>
</div>