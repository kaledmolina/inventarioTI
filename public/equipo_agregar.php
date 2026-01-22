<?php
// 1. INICIO DE SESIÓN SEGURO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

// Validar acceso
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir TODOS los datos del formulario
    $id_sucursal = $_POST['id_sucursal'];
    $codigo = trim($_POST['codigo_inventario']);
    $barcode = trim($_POST['codigo_barras'] ?? ''); // Nuevo campo
    $serie = trim($_POST['numero_serie']);
    $id_tipo = $_POST['id_tipo_equipo'];
    $id_marca = $_POST['id_marca'];
    $id_modelo = $_POST['id_modelo'];
    $tipo_adq = $_POST['tipo_adquisicion'];
    $caracteristicas = trim($_POST['caracteristicas']);
    $fecha = $_POST['fecha_adquisicion'];
    $proveedor = trim($_POST['proveedor']);
    $observaciones = trim($_POST['observaciones']);
    $estado = 'Disponible'; // Estado inicial por defecto

    // Validación básica
    if (empty($codigo) || empty($id_sucursal) || empty($id_tipo) || empty($id_marca)) {
        $mensaje = "Por favor complete los campos obligatorios marcados con (*).";
        $tipo_mensaje = "danger";
    } else {
        // Verificar duplicados
        $check = $conexion->prepare("SELECT id FROM equipos WHERE codigo_inventario = ?");
        $check->bind_param("s", $codigo);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $mensaje = "El código de inventario '$codigo' ya existe.";
            $tipo_mensaje = "warning";
        } else {
            // INSERTAR DATOS COMPLETOS
            $sql = "INSERT INTO equipos (
                        codigo_inventario, codigo_barras, numero_serie, id_sucursal, id_tipo_equipo, id_marca, id_modelo, 
                        fecha_adquisicion, tipo_adquisicion, caracteristicas, proveedor, observaciones, estado
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param(
                "sssiiiissssss",
                $codigo,
                $barcode,
                $serie,
                $id_sucursal,
                $id_tipo,
                $id_marca,
                $id_modelo,
                $fecha,
                $tipo_adq,
                $caracteristicas,
                $proveedor,
                $observaciones,
                $estado
            );

            if ($stmt->execute()) {
                header("Location: equipos.php?msg=guardado");
                exit();
            } else {
                $mensaje = "Error al guardar: " . $conexion->error;
                $tipo_mensaje = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}

// 2. INCLUIR HEADER
require_once '../templates/header.php';

// Consultas para llenar los selectores (Modelos se carga vía AJAX ahora)
$sucursales = $conexion->query("SELECT id, nombre FROM sucursales WHERE estado = 'Activo' ORDER BY nombre");
$tipos = $conexion->query("SELECT id, nombre FROM tipos_equipo WHERE estado = 'Activo' ORDER BY nombre");
$marcas = $conexion->query("SELECT id, nombre FROM marcas WHERE estado = 'Activo' ORDER BY nombre");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Registrar Nuevo Equipo</h1>
    <a href="equipos.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>
</div>

<?php if (!empty($mensaje)): ?>
    <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show shadow-sm" role="alert">
        <?php echo $mensaje; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-pc-display me-2"></i> Información del Equipo</h5>
    </div>
    <div class="card-body p-4">
        <form action="equipo_agregar.php" method="POST">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-bold">Sucursal <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_sucursal" required>
                        <option value="">Seleccione...</option>
                        <?php
                        $sucursal_fija = $_SESSION['user_sucursal_id'] ?? null;
                        if ($sucursales) {
                            mysqli_data_seek($sucursales, 0);
                            while ($row = $sucursales->fetch_assoc()) {
                                $selected = ($sucursal_fija == $row['id']) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Código Inventario <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="codigo_inventario" required placeholder="Ej: INV-001">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Código de Barras (Opcional)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="codigo_barras" id="codigo_barras"
                            placeholder="Escanee o ingrese el código de barras">
                        <button class="btn btn-outline-dark" type="button" id="btnScanBarcode">
                            <i class="bi bi-qr-code-scan"></i>
                        </button>
                    </div>
                    <div id="reader-barcode" class="mt-2" style="width: 100%; display:none;"></div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Número de Serie</label>
                    <input type="text" class="form-control" name="numero_serie"
                        placeholder="Ingrese el número de serie">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tipo de Equipo <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_tipo_equipo" required>
                        <option value="">Seleccione...</option>
                        <?php if ($tipos) {
                            mysqli_data_seek($tipos, 0);
                            while ($r = $tipos->fetch_assoc()) {
                                echo "<option value='{$r['id']}'>{$r['nombre']}</option>";
                            }
                        } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Marca <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_marca" id="id_marca" required>
                        <option value="">Seleccione...</option>
                        <?php if ($marcas) {
                            mysqli_data_seek($marcas, 0);
                            while ($r = $marcas->fetch_assoc()) {
                                echo "<option value='{$r['id']}'>{$r['nombre']}</option>";
                            }
                        } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Modelo</label>
                    <select class="form-select" name="id_modelo" id="id_modelo">
                        <option value="">Seleccione una marca primero</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipo de Adquisición</label>
                    <select class="form-select" name="tipo_adquisicion">
                        <option value="Propio">Propio</option>
                        <option value="Alquilado">Alquilado</option>
                        <option value="Leasing">Leasing</option>
                        <option value="Prestamo">Préstamo</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Características</label>
                    <input type="text" class="form-control" name="caracteristicas"
                        placeholder="Ej: Core i5, 16GB RAM...">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha de Adquisición</label>
                    <input type="date" class="form-control" name="fecha_adquisicion"
                        value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Proveedor</label>
                    <input type="text" class="form-control" name="proveedor" placeholder="Nombre del proveedor">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="3"></textarea>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="equipos.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">Registrar Equipo</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>

<script>
    $(document).ready(function () {
        $('#id_marca').on('change', function () {
            var idMarca = $(this).val();

            // Limpiar y mostrar "Cargando..."
            $('#id_modelo').html('<option value="">Cargando modelos...</option>');

            if (idMarca) {
                $.ajax({
                    url: 'obtener_modelos.php',
                    type: 'POST',
                    data: { id_marca: idMarca },
                    success: function (response) {
                        $('#id_modelo').html(response);
                    },
                    error: function () {
                        $('#id_modelo').html('<option value="">Error al cargar modelos</option>');
                    }
                });
            } else {
                $('#id_modelo').html('<option value="">Seleccione una marca primero</option>');
            }
        });

        // --- SCANNER LOGIC ---
        const btnScan = document.getElementById('btnScanBarcode');
        const readerDiv = document.getElementById('reader-barcode');
        const inputBarcode = document.getElementById('codigo_barras');
        let html5QrcodeScanner = null;

        if (btnScan) {
            btnScan.addEventListener('click', function () {
                if (readerDiv.style.display === 'none') {
                    readerDiv.style.display = 'block';
                    startScanner();
                } else {
                    stopScanner();
                }
            });
        }

        function startScanner() {
            html5QrcodeScanner = new Html5Qrcode("reader-barcode");
            const config = {
                fps: 10,
                qrbox: { width: 250, height: 150 },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.QR_CODE,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.UPC_A
                ]
            };

            html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess)
                .catch(err => {
                    console.error("Error iniciando cámara", err);
                    alert("No se pudo iniciar la cámara. Verifique permisos.");
                });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    readerDiv.style.display = 'none';
                    html5QrcodeScanner.clear();
                }).catch(err => console.error("Error deteniendo cámara", err));
            } else {
                readerDiv.style.display = 'none';
            }
        }


        function playBeep() {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(1200, audioCtx.currentTime); // 1200 Hz
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);

            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.1);
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Código escaneado: ${decodedText}`);
            playBeep();
            inputBarcode.value = decodedText;
            stopScanner();

            // Visual feedback
            inputBarcode.classList.add('is-valid');
            setTimeout(() => inputBarcode.classList.remove('is-valid'), 2000);
        }
    });
</script>