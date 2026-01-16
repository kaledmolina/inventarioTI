<div>
    <h1 class="h2 mb-4">Historial de Equipos Dados de Baja</h1>

    <div class="card">
        <div class="card-header">
            Listado de Bajas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Código Equipo</th>
                            <th>Marca / Modelo</th>
                            <th>Fecha de Baja</th>
                            <th>Motivo</th>
                            <th>Acta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bajas as $baja)
                            <tr>
                                <td>{{ $baja->equipo->codigo_inventario ?? 'N/A' }}</td>
                                <td>
                                    {{ $baja->equipo->marca->nombre ?? '' }} /
                                    {{ $baja->equipo->modelo->nombre ?? '' }}
                                </td>
                                <td>{{ $baja->fecha_baja ? $baja->fecha_baja->format('d/m/Y') : '-' }}</td>
                                <td>{{ $baja->motivo }}</td>
                                <td>
                                    @if ($baja->acta_baja_path)
                                        <a href="{{ asset('storage/bajas/' . $baja->acta_baja_path) }}" target="_blank"
                                            class="btn btn-info btn-sm" title="Ver Acta">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay equipos dados de baja.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $bajas->links() }}
            </div>
        </div>
    </div>
</div>