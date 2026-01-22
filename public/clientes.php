<?php
require_once '../templates/header.php';
require_once '../config/database.php';

// Validar permisos
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener clientes
$sql = "SELECT * FROM clientes ORDER BY fecha_registro DESC";
$resultado = $conexion->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de Clientes</h1>
    <a href="cliente_agregar.php" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-2"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablaClientes">
                <thead class="table-light">
                    <tr>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($cliente = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold">
                                    <?php echo htmlspecialchars($cliente['apellidos'] . ', ' . $cliente['nombres']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['dni']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['telefono'] ?? '-'); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['email'] ?? '-'); ?>
                                </td>
                                <td class="small text-muted"
                                    style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo htmlspecialchars($cliente['direccion'] ?? '-'); ?>
                                </td>
                                <td>
                                    <a href="cliente_editar.php?id=<?php echo $cliente['id']; ?>"
                                        class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay clientes registrados aún.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Si usas DataTables en el proyecto
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof simpleDatatables !== 'undefined') {
            new simpleDatatables.DataTable("#tablaClientes", {
                searchable: true,
                fixedHeight: true,
            });
        }
    });
</script>

<?php require_once '../templates/footer.php'; ?>