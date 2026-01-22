<?php
require_once '../templates/header.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Agregar Nuevo Cliente</h1>
    <a href="clientes.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>
</div>

<div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body p-4">
        <form action="procesar_cliente.php" method="POST">
            <input type="hidden" name="accion" value="crear">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombres *</label>
                    <input type="text" class="form-control" name="nombres" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Apellidos *</label>
                    <input type="text" class="form-control" name="apellidos" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">DNI / Identificación *</label>
                    <input type="text" class="form-control" name="dni" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Teléfono</label>
                    <input type="text" class="form-control" name="telefono">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Dirección</label>
                    <textarea class="form-control" name="direccion" rows="3"></textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-2"></i> Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>