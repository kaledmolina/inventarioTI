<div>
    <h1 class="h2 mb-4">Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="card-title mb-1">Total de Equipos</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $totalEquipos }}</h2>
                    </div>
                    <i class="bi bi-server fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-warning h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="card-title mb-1">Equipos Asignados</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $equiposAsignados }}</h2>
                    </div>
                    <i class="bi bi-person-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="card-title mb-1">Equipos Disponibles</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $equiposDisponibles }}</h2>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-primary">Equipos por Tipo</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    @if(count($tiposData) > 0)
                        <div style="width: 80%; height: 300px;">
                            <canvas id="chartTipos"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-pie-chart fs-1 opacity-25"></i>
                            <p class="mt-2">No hay datos suficientes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-primary">Equipos y Empleados por Sucursal</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    @if(count($sucursalLabels) > 0)
                        <div style="width: 100%; height: 300px;">
                            <canvas id="chartSucursales"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bar-chart-line fs-1 opacity-25"></i>
                            <p class="mt-2">No hay datos de sucursales</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("livewire:navigated", function () {
        initCharts();
    });

    // Also run on initial load if not using navigate
    document.addEventListener("DOMContentLoaded", function () {
        initCharts();
    });

    function initCharts() {
        // --- GRÁFICO 1: DONA (TIPOS) ---
        if (@json(count($tiposData) > 0)) {
            const ctx1 = document.getElementById('chartTipos');
            if (ctx1) {
                // Destroy existing chart if it exists to prevent double rendering
                const existingChart1 = Chart.getChart(ctx1);
                if (existingChart1) existingChart1.destroy();

                new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: @json($tiposLabels),
                        datasets: [{
                            label: 'Cantidad',
                            data: @json($tiposData),
                            backgroundColor: [
                                '#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6', '#6366f1'
                            ],
                            borderWidth: 2,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        }
                    }
                });
            }
        }

        // --- GRÁFICO 2: BARRAS (SUCURSALES) ---
        if (@json(count($sucursalLabels) > 0)) {
            const ctx2 = document.getElementById('chartSucursales');
            if (ctx2) {
                // Destroy existing chart if it exists
                const existingChart2 = Chart.getChart(ctx2);
                if (existingChart2) existingChart2.destroy();

                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: @json($sucursalLabels),
                        datasets: [
                            {
                                label: 'Equipos',
                                data: @json($dataEquipos),
                                backgroundColor: '#3b82f6', // Azul
                                borderRadius: 4
                            },
                            {
                                label: 'Empleados',
                                data: @json($dataEmpleados),
                                backgroundColor: '#f59e0b', // Amarillo/Naranja
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', align: 'end', labels: { usePointStyle: true } }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [2, 4] }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        }
    }
</script>
@endscript