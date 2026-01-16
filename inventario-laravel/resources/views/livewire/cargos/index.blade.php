<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4">Gestión de Cargos</h2>
        <a href="{{ route('cargos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Cargo
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre del Cargo</th>
                            <th>Área Perteneciente</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cargos as $cargo)
                            <tr>
                                <td>{{ $cargo->nombre }}</td>
                                <td>{{ $cargo->area->nombre ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $cargo->estado === 'Activo' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $cargo->estado }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('cargos.edit', $cargo->id) }}" class="btn btn-warning btn-sm"
                                        title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No hay cargos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $cargos->links() }}
            </div>
        </div>
    </div>
</div>