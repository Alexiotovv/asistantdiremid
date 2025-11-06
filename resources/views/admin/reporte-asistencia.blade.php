<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Asistencias - {{ $anio }}-{{ $mes }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .no-print { display: none; }
        @media print {
            .no-print { display: block; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="header">
        <h2>Reporte de Asistencias</h2>
        <h4>Mes: {{ \DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $anio }}</h4>
        <button class="btn btn-primary no-print" onclick="window.print()">Imprimir / Guardar PDF</button>
    </div>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre Completo</th>
                <th>Oficina</th>
                @foreach($dias_mes as $dia)
                    <th>{{ $dia->format('d') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($resultados as $fila)
                <tr>
                    <td>{{ $fila['dni'] }}</td>
                    <td>{{ $fila['nombre_completo'] }}</td>
                    <td>{{ $fila['oficina'] }}</td>
                    @foreach($fila['dias'] as $dia)
                        <td>{{ $dia ?: '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    // Opcional: centrar la tabla en impresión
    window.addEventListener('beforeprint', () => {
        document.body.style.zoom = "70%";
    });
    window.addEventListener('afterprint', () => {
        document.body.style.zoom = "100%";
    });
</script>

</body>
</html>