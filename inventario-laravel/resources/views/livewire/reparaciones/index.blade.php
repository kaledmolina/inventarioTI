<div>
    <h1 class="h2 mb-4">Historial de Reparaciones</h1>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <label for="estado" class="form-label me-2 mb-0">Filtrar por estado:</label>
                <select wire:model.live="filtroEstado" id="estado" class="form-select w-auto">
                    <option value="En Proceso">En Proceso</option>
                    <option value="Finalizada">Finalizada</option>
                    <option value="Todas">Todas</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Listado de Reparaciones
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código Equipo</th>
                            <th>Marca / Modelo</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Salida</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reparaciones as $reparacion)
                            <tr>
                                <td>{{ $reparacion->equipo->codigo_inventario ?? 'N/A' }}</td>
                                <td>
                                    {{ $reparacion->equipo->marca->nombre ?? '' }} /
                                    {{ $reparacion->equipo->modelo->nombre ?? '' }}
                                </td>
                                <td>{{ $reparacion->fecha_ingreso ? $reparacion->fecha_ingreso->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $reparacion->fecha_salida ? $reparacion->fecha_salida->format('d/m/Y') : '---' }}
                                </td>
                                <td>{{ $reparacion->motivo }}</td>
                                <td>
                                    <span
                                        class="badge {{ $reparacion->estado_reparacion == 'En Proceso' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                        {{ $reparacion->estado_reparacion }}
                                    </span>
                                </td>
                                <td>
                                    @if ($reparacion->estado_reparacion == 'En Proceso')
                                        <a href="{{ route('reparaciones.finalizar', $reparacion->id) }}"
                                            class="btn btn-success btn-sm" title="Finalizar Reparación">
                                            <i class="bi bi-check-circle-fill"></i> Finalizar
                                        </a>
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay reparaciones que coincidan con los filtros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reparaciones->links() }}
            </div>
        </div>
    </div>
</div>