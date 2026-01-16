<?php
require_once '../templates/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h2">Escanear Código QR</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-md">
            <div class="card-header bg-white">
                <i class="bi bi-camera-video me-2"></i> Escáner de Cámara
            </div>
            <div class="card-body text-center">
                <div id="reader" style="width: 100%; min-height: 300px; background-color: #f8fafc; border-radius: 8px;">
                </div>

                <div id="result-container" class="mt-3 d-none">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i> Código Detectado: <span id="scanned-result"
                            class="fw-bold"></span>
                    </div>
                    <p class="text-muted">Redirigiendo...</p>
                </div>

                <div class="mt-3 text-muted small">
                    <i class="bi bi-info-circle"></i> Asegúrate de permitir el acceso a tu cámara.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true
        });

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Scan result: ${decodedText}`, decodedResult);

            // Detener el escaneo tras éxito
            scanner.clear();

            // Mostrar feedback visual
            document.getElementById('result-container').classList.remove('d-none');
            document.getElementById('scanned-result').innerText = decodedText;

            // Lógica de Redirección
            // Si el QR es una URL completa de nuestro sistema, vamos allí.
            // Si es solo un ID o Texto, podríamos buscarlo (aquí asumimos URL completa generada por el sistema)

            // Validación simple de seguridad: verificar que la URL pertenezca a nuestro dominio o sea relativa
            try {
                const url = new URL(decodedText);
                // Si la URL escaneada contiene 'equipo_detalle.php', redirigir
                if (url.pathname.includes('equipo_detalle.php')) {
                    setTimeout(() => {
                        window.location.href = decodedText;
                    }, 500); // Pequeña pausa para ver el check verde
                } else {
                    alert("El código QR escaneado no parece ser un equipo válido del sistema.");
                    // Reiniciar escáner si se desea
                    location.reload();
                }
            } catch (e) {
                // Si no es URL válida, quizás es un ID directo? 
                // Por ahora asumimos que generamos URLs completas.
                alert("Código no válido: " + decodedText);
                location.reload();
            }
        }

        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }

        scanner.render(onScanSuccess, onScanFailure);
    });
</script>

<?php require_once '../templates/footer.php'; ?>