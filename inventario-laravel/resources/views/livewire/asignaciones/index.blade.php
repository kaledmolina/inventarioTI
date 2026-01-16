<div>
    <h1 class="h2 mb-3">Historial de Asignaciones</h1>

    <div class="card mb-4">
        <div class="card-header"><i class="bi bi-funnel-fill"></i> Filtros y Reportes</div>
        <div class="card-body">
            <div class="row g-3">
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
                    <label class="form-label">Empleado</label>
                    <select wire:model.live="empleadoId" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($empleados_list as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->apellidos }}, {{ $emp->nombres }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Equipo (Código)</label>
                    <select wire:model.live="equipoId" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($equipos_list as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->codigo_inventario }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado Asignación</label>
                    <select wire:model.live="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Activa">Activa</option>
                        <option value="Finalizada">Finalizada</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha Entrega (Desde)</label>
                    <input wire:model.live="fecha_desde" type="date" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha Entrega (Hasta)</label>
                    <input wire:model.live="fecha_hasta" type="date" class="form-control form-control-sm">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button wire:click="$set('estado', '')" class="btn btn-secondary btn-sm">Limpiar</button>
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
        <h2 class="h4">Listado de Asignaciones</h2>
        <a href="{{ route('asignaciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Nueva
            Asignación</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Empleado</th>
                            <th>Equipo</th>
                            <th>Fecha Entrega</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                            <th>Acta Entrega</th>
                            <th>Acta Devolución</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($asignaciones as $asignacion)
                            <tr>
                                <td>{{ $asignacion->empleado->apellidos }}, {{ $asignacion->empleado->nombres }}</td>
                                <td>{{ $asignacion->equipo->codigo_inventario }}
                                    ({{ $asignacion->equipo->marca->nombre ?? '' }})</td>
                                <td>{{ \Carbon\Carbon::parse($asignacion->fecha_entrega)->format('d/m/Y') }}</td>
                                <td>
                                    @if($asignacion->fecha_devolucion)
                                        {{ \Carbon\Carbon::parse($asignacion->fecha_devolucion)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge {{ !$asignacion->fecha_devolucion ? 'bg-success' : 'bg-secondary' }}">
                                        {{ !$asignacion->fecha_devolucion ? 'Activa' : 'Finalizada' }}
                                    </span>
                                </td>
                                <td>
                                    @if($asignacion->acta_firmada_path)
                                        <a href="{{ asset('storage/' . $asignacion->acta_firmada_path) }}" target="_blank"
                                            class="btn btn-info btn-sm" title="Ver Acta Entrega">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-outline-primary btn-sm" title="Subir Acta Entrega"><i
                                                class="bi bi-upload"></i></button>
                                    @endif
                                </td>
                                <td>
                                    @if($asignacion->fecha_devolucion)
                                        @if($asignacion->acta_devolucion_path)
                                            <a href="{{ asset('storage/' . $asignacion->acta_devolucion_path) }}" target="_blank"
                                                class="btn btn-info btn-sm" title="Ver Acta Devolución">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-outline-danger btn-sm" title="Subir Acta Devolución"><i
                                                    class="bi bi-upload"></i></button>
                                        @endif
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-secondary btn-sm" title="Imprimir Acta Entrega"><i
                                                class="bi bi-printer"></i></button>

                                        @if(!$asignacion->fecha_devolucion)
                                            <a href="{{ route('asignaciones.devolver', $asignacion->id) }}"
                                                class="btn btn-danger btn-sm" title="Registrar Devolución"><i
                                                    class="bi bi-arrow-return-left"></i></a>
                                        @else
                                            <button class="btn btn-primary btn-sm" title="Ver Detalle Devolución"><i
                                                    class="bi bi-eye"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">No se encontraron asignaciones.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-4">
                {{ $asignaciones->links() }}
            </div>
        </div>
    </div>
</div>