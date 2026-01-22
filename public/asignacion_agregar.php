<?php
require_once '../templates/header.php';

$es_admin_general = ($_SESSION['user_sucursal_id'] === null);
$id_sucursal_usuario = (int) $_SESSION['user_sucursal_id'];

// Cargar sucursales SOLO si es admin general
$sucursales = null;
if ($es_admin_general) {
    $sucursales = $conexion->query("SELECT id, nombre FROM sucursales WHERE estado = 'Activo' ORDER BY nombre");
}
?>


<!-- MODIFICACIÓN: Selección de Destinatario (Empleado o Cliente) -->
<div class="mb-3">
    <label class="form-label fw-bold">Asignar a:</label>
    <div class="d-flex gap-4">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="tipo_asignacion" id="radioEmpleado" value="empleado"
                checked>
            <label class="form-check-label" for="radioEmpleado">
                <i class="bi bi-person-badge me-1"></i> Empleado
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="tipo_asignacion" id="radioCliente" value="cliente">
            <label class="form-check-label" for="radioCliente">
                <i class="bi bi-people-fill me-1"></i> Cliente Externo
            </label>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="asignar_equipo.php" method="POST" id="formAsignacion">
            <input type="hidden" name="tipo_asignacion_submit" id="tipo_asignacion_submit" value="empleado">

            <div class="mb-3">
                <label for="selectSucursal" class="form-label">Seleccionar Sucursal (Ubicación del Equipo) *</label>
                <select class="form-select" id="selectSucursal" name="id_sucursal" <?php echo !$es_admin_general ? 'disabled' : 'required'; ?>>
                    <?php if ($es_admin_general): ?>
                        <option value="">Seleccione...</option>
                        <?php while ($s = $sucursales->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['nombre']); ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <?php $nombre_sucursal = $conexion->query("SELECT nombre FROM sucursales WHERE id = $id_sucursal_usuario")->fetch_assoc()['nombre']; ?>
                        <option value="<?php echo $id_sucursal_usuario; ?>" selected>
                            <?php echo htmlspecialchars($nombre_sucursal); ?>
                        </option>
                    <?php endif; ?>
                </select>
                <?php if (!$es_admin_general): ?>
                    <input type="hidden" name="id_sucursal" value="<?php echo $id_sucursal_usuario; ?>" />
                <?php endif; ?>
            </div>

            <!-- Div Empleado -->
            <div class="mb-3" id="divEmpleado">
                <label for="selectEmpleado" class="form-label">Seleccionar Empleado *</label>
                <select class="form-select" id="selectEmpleado" name="id_empleado" <?php echo $es_admin_general ? 'disabled' : ''; ?>>
                    <option value="">Cargando...</option>
                </select>
            </div>

            <!-- Div Cliente (Nuevo) -->
            <div class="mb-3 d-none" id="divCliente">
                <label for="selectCliente" class="form-label">Seleccionar Cliente *</label>
                <div class="input-group">
                    <select class="form-select" id="selectCliente" name="id_cliente" disabled>
                        <option value="">Cargando clientes...</option>
                    </select>
                    <a href="cliente_agregar.php" class="btn btn-outline-secondary" target="_blank"
                        title="Crear Nuevo Cliente">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
                <div class="form-text">Si el cliente no aparece, regístrelo primero.</div>
            </div>

            <div class="mb-3">
                <label for="selectEquipo" class="form-label">Seleccionar Equipo *</label>
                <div class="input-group">
                    <select class="form-select" id="selectEquipo" name="id_equipo" required <?php echo $es_admin_general ? 'disabled' : ''; ?>>
                        <option value="">Cargando...</option>
                    </select>
                    <button class="btn btn-outline-dark" type="button" id="btnScan">
                        <i class="bi bi-qr-code-scan"></i>
                    </button>
                </div>
                <div id="reader" class="mt-2" style="width: 100%; display:none;"></div>
            </div>

            <div class="mb-3">
                <label for="observaciones_entrega" class="form-label">Observaciones de la Entrega</label>
                <textarea class="form-control" id="observaciones_entrega" name="observaciones_entrega" rows="3"
                    placeholder="Ej: Se entrega con cargador y maletín."></textarea>
            </div>

            <hr>
            <a href="asignaciones.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Asignar Equipo</button>
        </form>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const selectSucursal = document.getElementById('selectSucursal');
        const selectEmpleado = document.getElementById('selectEmpleado');
        const selectEquipo = document.getElementById('selectEquipo');
        const btnScan = document.getElementById('btnScan');
        const readerDiv = document.getElementById('reader');
        let html5QrcodeScanner = null;

        // --- LÓGICA DE ESCÁNER ---
        btnScan.addEventListener('click', function () {
            if (readerDiv.style.display === 'none') {
                readerDiv.style.display = 'block';
                startScanner();
            } else {
                stopScanner();
            }
        });

        function startScanner() {
            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = {
                fps: 10,
                qrbox: { width: 300, height: 150 },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.QR_CODE,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.UPC_A
                ]
            };

            // Preferir cámara trasera
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

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Código escaneado: ${decodedText}`);

            // Buscar en el select
            let encontrado = false;
            for (let i = 0; i < selectEquipo.options.length; i++) {
                let option = selectEquipo.options[i];
                let optionText = option.text;
                let optionBarcode = option.getAttribute('data-barcode');

                // Comparar con el texto (QR/Inventario) O con el barcode
                if (
                    optionText.toUpperCase().includes(decodedText.toUpperCase()) ||
                    (optionBarcode && optionBarcode.toUpperCase() === decodedText.toUpperCase())
                ) {
                    selectEquipo.selectedIndex = i;
                    encontrado = true;
                    stopScanner(); // Detener escáner al encontrar

                    // Efecto visual de éxito
                    selectEquipo.classList.add('is-valid');
                    setTimeout(() => selectEquipo.classList.remove('is-valid'), 2000);
                    break;
                }
            }

            if (!encontrado) {
                alert(`El código ${decodedText} no coincide con ningún equipo disponible en esta sucursal.`);
            }
        }

        // --- LÓGICA DE ELEMENTOS NUEVOS ---
        const radioEmpleado = document.getElementById('radioEmpleado');
        const radioCliente = document.getElementById('radioCliente');
        const divEmpleado = document.getElementById('divEmpleado');
        const divCliente = document.getElementById('divCliente');
        const selectCliente = document.getElementById('selectCliente');
        const inputTipoAsignacion = document.getElementById('tipo_asignacion_submit');

        const esAdminGeneral = <?php echo $es_admin_general ? 'true' : 'false'; ?>;

        // --- LÓGICA DE TOGGLE ---
        function toggleDestinatario() {
            if (radioEmpleado.checked) {
                divEmpleado.classList.remove('d-none');
                divCliente.classList.add('d-none');
                selectEmpleado.required = true;
                selectCliente.required = false;
                selectCliente.disabled = true; // Deshabilitar para no enviar
                if (selectEmpleado.options.length > 1) selectEmpleado.disabled = false;
                inputTipoAsignacion.value = 'empleado';
            } else {
                divEmpleado.classList.add('d-none');
                divCliente.classList.remove('d-none');
                selectEmpleado.required = false;
                selectCliente.required = true;
                selectEmpleado.disabled = true; // Deshabilitar

                // Cargar clientes si es la primera vez o siempre
                if (selectCliente.options.length <= 1) {
                    cargarClientes();
                } else {
                    selectCliente.disabled = false;
                }
                inputTipoAsignacion.value = 'cliente';
            }
        }

        radioEmpleado.addEventListener('change', toggleDestinatario);
        radioCliente.addEventListener('change', toggleDestinatario);

        function cargarClientes() {
            selectCliente.innerHTML = '<option value="">Cargando...</option>';
            fetch('obtener_clientes.php')
                .then(r => r.json())
                .then(data => {
                    selectCliente.innerHTML = '<option value="">Seleccionar Cliente *</option>';
                    if (data && data.length > 0) {
                        data.forEach(c => {
                            selectCliente.add(new Option(c.completo, c.id));
                        });
                        selectCliente.disabled = false;
                    } else {
                        selectCliente.innerHTML = '<option value="">No hay clientes registrados</option>';
                    }
                })
                .catch(e => {
                    console.error(e);
                    selectCliente.innerHTML = '<option value="">Error al cargar</option>';
                });
        }

        // --- LÓGICA EXISTENTE DE CARGA DE DROPDOWNS ---

        function cargarDropdowns(sucursalId) {
            if (!sucursalId) {
                selectEmpleado.innerHTML = '<option value="">Seleccione una sucursal</option>';
                selectEquipo.innerHTML = '<option value="">Seleccione una sucursal</option>';
                selectEmpleado.disabled = true;
                selectEquipo.disabled = true;
                return;
            }

            selectEmpleado.innerHTML = '<option value="">Cargando...</option>';
            selectEquipo.innerHTML = '<option value="">Cargando...</option>';
            selectEmpleado.disabled = true;
            selectEquipo.disabled = true;

            // 1. Fetch Empleados
            const fetchEmpleados = fetch(`obtener_empleados_por_sucursal.php?id_sucursal=${sucursalId}`)
                .then(response => response.json())
                .then(empleados => {
                    selectEmpleado.innerHTML = '<option value="">Seleccionar Empleado *</option>';
                    if (empleados && !empleados.error && empleados.length > 0) {
                        empleados.forEach(emp => {
                            const option = new Option(`${emp.apellidos}, ${emp.nombres} (DNI: ${emp.dni})`, emp.id);
                            selectEmpleado.add(option);
                        });
                        if (radioEmpleado.checked) selectEmpleado.disabled = false;
                    } else {
                        selectEmpleado.innerHTML = '<option value="">No hay empleados activos en esta sucursal</option>';
                    }
                });

            // 2. Fetch Equipos
            const fetchEquipos = fetch(`obtener_equipos_por_sucursal.php?id_sucursal=${sucursalId}`)
                .then(response => response.json())
                .then(equipos => {
                    selectEquipo.innerHTML = '<option value="">Seleccionar Equipo *</option>';
                    if (equipos && !equipos.error && equipos.length > 0) {
                        equipos.forEach(eq => {
                            const option = new Option(`${eq.codigo_inventario} (${eq.marca_nombre} ${eq.modelo_nombre})`, eq.id);
                            // Guardar el código de barras en un atributo data
                            if (eq.codigo_barras) {
                                option.setAttribute('data-barcode', eq.codigo_barras);
                            }
                            selectEquipo.add(option);
                        });
                        selectEquipo.disabled = false;
                    } else {
                        selectEquipo.innerHTML = '<option value="">No hay equipos disponibles en esta sucursal</option>';
                    }
                });

            // Esperar a que ambas promesas se resuelvan
            Promise.all([fetchEmpleados, fetchEquipos]).catch(error => {
                console.error('Error al cargar datos:', error);
                selectEmpleado.innerHTML = '<option value="">Error al cargar</option>';
                selectEquipo.innerHTML = '<option value="">Error al cargar</option>';
            });
        }

        // Si es admin general, esperar a que cambie la sucursal
        if (esAdminGeneral) {
            selectSucursal.addEventListener('change', function () {
                cargarDropdowns(this.value);
            });
        } else {
            // Si es admin de sucursal, cargar los datos inmediatamente
            cargarDropdowns(selectSucursal.value);
        }
    }); // End DOMContentLoaded

</script>


<?php require_once '../templates/footer.php'; ?>