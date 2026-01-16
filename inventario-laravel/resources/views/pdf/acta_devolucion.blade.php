<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Acta de Devolución</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11pt;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }

        .header p {
            font-size: 10pt;
            font-style: italic;
            margin: 2px;
        }

        .section-title {
            font-weight: bold;
            font-size: 12pt;
            margin-top: 15px;
            margin-bottom: 5px;
            background-color: #f0f0f0;
            padding: 5px;
        }

        .row-data {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .label {
            display: table-cell;
            width: 35%;
            border: 1px solid #000;
            padding: 4px;
            font-weight: bold;
            background-color: #fafafa;
        }

        .value {
            display: table-cell;
            width: 65%;
            border: 1px solid #000;
            padding: 4px;
        }

        .obs-box {
            margin-top: 5px;
            border: 1px solid #000;
            width: 100%;
        }

        .obs-label {
            background-color: #fafafa;
            padding: 5px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .obs-value {
            padding: 5px;
            min-height: 40px;
        }

        .signatures {
            width: 100%;
            margin-top: 50px;
        }

        .sig-block {
            width: 45%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }

        .line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
            width: 80%;
            margin-left: 10%;
        }

        .date-right {
            text-align: right;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .photo-note {
            font-size: 9pt;
            font-style: italic;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Acta de Devolución de Equipo Informático</h1>
        <p>Sucursal: {{ $empleado->sucursal->nombre ?? 'General' }}</p>
    </div>

    <div class="date-right">
        Fecha de Devolución: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}
    </div>

    <div class="section-title">1. Datos del Colaborador que Devuelve</div>
    <div class="row-data">
        <div class="label">Nombres y Apellidos:</div>
        <div class="value">{{ $empleado->apellidos }}, {{ $empleado->nombres }}</div>
    </div>
    <div class="row-data">
        <div class="label">DNI:</div>
        <div class="value">{{ $empleado->dni }}</div>
    </div>
    <div class="row-data">
        <div class="label">Cargo:</div>
        <div class="value">{{ $empleado->cargo->nombre ?? 'N/A' }}</div>
    </div>

    <div class="section-title">2. Datos del Equipo Devuelto</div>
    <div class="row-data">
        <div class="label">Código de Inventario:</div>
        <div class="value">{{ $equipo->codigo_inventario }}</div>
    </div>
    <div class="row-data">
        <div class="label">Tipo de Equipo:</div>
        <div class="value">{{ $equipo->tipo_equipo->nombre ?? 'N/A' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Marca y Modelo:</div>
        <div class="value">{{ $equipo->marca->nombre ?? '' }} {{ $equipo->modelo->nombre ?? '' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Número de Serie:</div>
        <div class="value">{{ $equipo->numero_serie }}</div>
    </div>

    <div class="section-title">3. Detalles de la Devolución</div>
    <div class="row-data">
        <div class="label">Equipo Recibido por:</div>
        <div class="value">{{ $user->name ?? 'Usuario TI' }}</div>
    </div>

    <div class="obs-box">
        <div class="obs-label">Observaciones:</div>
        <div class="obs-value">{!! nl2br(e($asignacion->observaciones_devolucion ?? 'Sin observaciones.')) !!}</div>
    </div>

    @if($asignacion->imagen_devolucion_1 || $asignacion->imagen_devolucion_2 || $asignacion->imagen_devolucion_3)
        <div class="photo-note">
            * Evidencia fotográfica adjunta en el sistema digital.
        </div>
    @endif

    <div class="signatures">
        <div class="sig-block">
            <div class="line"></div>
            <strong>Firma del Empleado (Devuelve)</strong><br>
            {{ $empleado->apellidos }}, {{ $empleado->nombres }}<br>
            DNI: {{ $empleado->dni }}
        </div>
        <div class="sig-block" style="float: right;">
            <div class="line"></div>
            <strong>Recibido por (TI)</strong><br>
            {{ $user->name ?? 'Usuario TI' }}<br>
            Área de TI
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 9;
            $font = $fontMetrics->getFont("sans-serif", "italic");
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $pdf->page_text($pdf->get_width() - $width - 20, $pdf->get_height() - 20, $text, $font, $size);
        }
    </script>
</body>

</html>