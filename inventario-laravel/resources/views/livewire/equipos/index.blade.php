<div>
    <h1 class="h2 mb-4">Gestión de Equipos</h1>

    <div class="card mb-4">
        <div class="card-header"><i class="bi bi-funnel-fill me-2"></i> Filtros y Reportes</div>
        <div class="card-body">
            <!-- Legacy Filter Form Structure -->
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Código de Inventario</label>
                    <input wire:model.live.debounce.300ms="codigo_inventario" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Número de Serie</label>
                    <input wire:model.live.debounce.300ms="numero_serie" type="text" class="form-control form-control-sm">
                </div>
                
                @if(!$userSucursal)
                <div class="col-md-3">
                    <label class="form-label">Sucursal</label>
                    <select wire:model.live="sucursalId" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <label class="form-label">Tipo de Equipo</label>
                    <select wire:model.live="tipoId" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Marca</label>
                    <select wire:model.live="marcaId" class="form-select form-select-sm">
                        <option value="">Todas</option>
                         @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select wire:model.live="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Disponible">Disponible</option>
                        <option value="Asignado">Asignado</option>
                        <option value="En Reparación">En Reparación</option>
                        <option value="De Baja">De Baja</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <!-- In Livewire filtering is automatic, so 'Filtrar' just refreshes or is decorative, strictly we can make it a reset -->
                     <button wire:click="$set('search', '')" class="btn btn-secondary btn-sm">Limpiar</button>
                </div>
            </div>
            <hr>
             <div class="d-flex gap-2">
                <button type="button" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                <button type="button" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
                <button type="button" class="btn btn-info"><i class="bi bi-printer"></i> Imprimir</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Inventario Actual</span>
            <button class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Registrar Nuevo Equipo</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Sucursal</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Marca / Modelo</th>
                            <th>N/S</th>
                            <th>Fecha Adquisición</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipos as $equipo)
                            <tr>
                                <td>{{ $equipo->sucursal->nombre ?? 'N/A' }}</td>
                                <td>{{ $equipo->codigo_inventario ?? 'N/A' }}</td>
                                <td>{{ $equipo->tipo_equipo->nombre ?? 'N/A' }}</td>
                                <td>{{ ($equipo->marca->nombre ?? '') . ' / ' . ($equipo->modelo->nombre ?? '') }}</td>
                                <td>{{ $equipo->numero_serie ?? 'N/A' }}</td>
                                <td>{{ $equipo->fecha_adquisicion ? \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'Disponible' => 'bg-success',
                                            'Asignado' => 'bg-primary',
                                            'En Reparación' => 'bg-warning text-dark',
                                            'De Baja' => 'bg-danger'
                                        ];
                                        $class = $badges[$equipo->estado] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $class }}">
                                        {{ $equipo->estado }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-primary btn-sm" title="Ver Detalle"><i class="bi bi-eye-fill"></i></button>
                                        <button class="btn btn-warning btn-sm" title="Editar Equipo"><i class="bi bi-pencil-fill"></i></button>
                                        
                                        @if($equipo->estado === 'Disponible')
                                            <button class="btn btn-info btn-sm" title="Enviar a Reparación"><i class="bi bi-wrench"></i></button>
                                        @endif

                                        @if($equipo->estado !== 'Asignado' && $equipo->estado !== 'De Baja')
                                            <button class="btn btn-danger btn-sm" title="Dar de Baja"><i class="bi bi-trash-fill"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                             <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">No se encontraron equipos.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="d-flex justify-content-end mt-4">
                {{ $equipos->links() }}
            </div>
        </div>
    </div>
</div>