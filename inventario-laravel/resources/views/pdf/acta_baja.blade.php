<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta de Baja de Equipo</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            float: left;
            width: 45%;
            text-align: center;
            margin-right: 5%;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ACTA DE BAJA DE EQUIPO</h1>
        <p>Fecha: {{ $fecha }}</p>
    </div>

    <div class="section">
        <div class="section-title">1. DATOS DEL EQUIPO</div>
        <table>
            <tr>
                <th>Código Inventario</th>
                <td>{{ $equipo->codigo_inventario }}</td>
                <th>Tipo Equipo</th>
                <td>{{ $equipo->tipo->nombre }}</td>
            </tr>
            <tr>
                <th>Marca</th>
                <td>{{ $equipo->marca->nombre }}</td>
                <th>Modelo</th>
                <td>{{ $equipo->modelo->nombre }}</td>
            </tr>
            <tr>
                <th>Serie</th>
                <td>{{ $equipo->numero_serie }}</td>
                <th>Fecha Adquisición</th>
                <td>{{ $equipo->fecha_adquisicion }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. MOTIVO DE LA BAJA</div>
        <table>
            <tr>
                <th>Motivo Principal</th>
            </tr>
            <tr>
                <td>{{ $motivo }}</td>
            </tr>
        </table>
    </div>

    @if($observaciones)
        <div class="section">
            <div class="section-title">3. OBSERVACIONES ADICIONALES</div>
            <p>{{ $observaciones }}</p>
        </div>
    @endif

    <div class="section">
        <p>Se certifica que el equipo descrito anteriormente ha sido dado de baja del inventario activo por el motivo
            indicado, quedando inhabilitado para su uso operativo.</p>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Aprobado por (Jefe TI)</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Responsable Inventario</p>
        </div>
    </div>
</body>

</html>