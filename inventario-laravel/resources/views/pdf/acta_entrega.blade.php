<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Acta de Entrega</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
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

        .characteristics {
            margin-top: 5px;
            border: 1px solid #000;
            width: 100%;
        }

        .char-label {
            background-color: #fafafa;
            padding: 5px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .char-value {
            padding: 5px;
            min-height: 40px;
        }

        .declaration {
            text-align: justify;
            margin-top: 20px;
            margin-bottom: 40px;
            line-height: 1.5;
            font-size: 10pt;
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

        .page-num {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            text-align: center;
            font-style: italic;
            font-size: 9pt;
        }

        .date-right {
            text-align: right;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Acta de Entrega de Equipo Informático</h1>
    </div>

    <div class="date-right">
        Fecha de Entrega: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}
    </div>

    <div class="section-title">1. Datos del Colaborador que Recibe</div>
    <div class="row-data">
        <div class="label">Nombres y Apellidos:</div>
        <div class="value">{{ $empleado->apellidos }}, {{ $empleado->nombres }}</div>
    </div>
    <div class="row-data">
        <div class="label">DNI:</div>
        <div class="value">{{ $empleado->dni }}</div>
    </div>
    <div class="row-data">
        <div class="label">Sucursal:</div>
        <div class="value">{{ $empleado->sucursal->nombre ?? 'N/A' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Área:</div>
        <div class="value">{{ $empleado->area->nombre ?? 'N/A' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Cargo:</div>
        <div class="value">{{ $empleado->cargo->nombre ?? 'N/A' }}</div>
    </div>

    <div class="section-title">2. Datos del Equipo Entregado</div>
    <div class="row-data">
        <div class="label">Tipo de Equipo:</div>
        <div class="value">{{ $equipo->tipo_equipo->nombre ?? 'N/A' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Marca:</div>
        <div class="value">{{ $equipo->marca->nombre ?? 'N/A' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Modelo:</div>
        <div class="value">{{ $equipo->modelo->nombre ?? 'N/A' }}</div>
    </div>
    <div class="row-data">
        <div class="label">Código de Inventario:</div>
        <div class="value">{{ $equipo->codigo_inventario }}</div>
    </div>
    <div class="row-data">
        <div class="label">Número de Serie:</div>
        <div class="value">{{ $equipo->numero_serie }}</div>
    </div>

    <div class="characteristics">
        <div class="char-label">Características:</div>
        <div class="char-value">{{ $equipo->caracteristicas ?? 'N/A' }}</div>
    </div>

    <div class="characteristics" style="margin-top: 5px;">
        <div class="char-label">Observaciones de Entrega:</div>
        <div class="char-value">{{ $asignacion->observaciones_entrega ?? 'Ninguna.' }}</div>
    </div>

    <div class="declaration">
        Declaro haber recibido el equipo detallado en el presente documento, el cual se encuentra en óptimas condiciones
        operativas. Me comprometo a utilizarlo exclusivamente para fines laborales, cuidarlo y reportar cualquier
        incidencia al área de Soporte TI.
    </div>

    <div class="signatures">
        <div class="sig-block">
            <div class="line"></div>
            <strong>Firma del Colaborador</strong><br>
            {{ $empleado->apellidos }}, {{ $empleado->nombres }}<br>
            DNI: {{ $empleado->dni }}
        </div>
        <div class="sig-block" style="float: right;">
            <div class="line"></div>
            <strong>Entregado por (TI)</strong><br>
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