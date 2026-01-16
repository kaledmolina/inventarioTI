<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Bitácora Global de Movimientos</h2>
        <div>
            <!-- Filters or Export buttons could go here -->
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Search -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="form-control border-start-0 ps-0"
                            placeholder="Buscar por acción, equipo, o usuario...">
                    </div>
                </div>
            </div>

            <!-- Timeline / Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Fecha/Hora</th>
                            <th scope="col">Acción</th>
                            <th scope="col">Equipo</th>
                            <th scope="col">Usuario Responsable</th>
                            <th scope="col">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movimientos as $log)
                            <tr>
                                <td class="text-secondary small">{{ $log->created_at->format('d/m/Y H:i A') }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'ASIGNACION' => 'bg-success',
                                            'DEVOLUCION' => 'bg-info',
                                            'REPARACION' => 'bg-warning text-dark',
                                            'BAJA' => 'bg-danger',
                                            'EDICION' => 'bg-secondary',
                                        ];
                                        $badgeClass = $badges[$log->accion] ?? 'bg-primary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $log->accion }}</span>
                                </td>
                                <td>
                                    @if($log->equipo)
                                        <a href="#" class="text-decoration-none fw-bold">
                                            {{ $log->equipo->codigo_inventario }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $log->equipo->tipo_equipo->nombre ?? 'Equipo' }}</small>
                                    @else
                                        <span class="text-muted fst-italic">General</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center me-2"
                                            style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ substr($log->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <small>{{ $log->user->name ?? 'Sistema' }}</small>
                                    </div>
                                </td>
                                <td class="text-muted small">
                                    {{ Str::limit($log->detalles, 60) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No hay movimientos registrados en la bitácora.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $movimientos->links() }}
            </div>
        </div>
    </div>
</div>