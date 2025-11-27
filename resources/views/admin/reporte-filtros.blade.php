@extends('layouts.base')

@section('title') Reporte de Asistencias @endsection

@section('content')
<div class="container-fluid">
    <h2>📊 Reporte de Asistencias</h2>
    
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reporte.html') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="mes" class="form-label">Mes</label>
                        <select name="mes" id="mes" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $mes ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="anio" class="form-label">Año</label>
                        <select name="anio" id="anio" class="form-select">
                            @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                <option value="{{ $i }}" {{ $i == $anio ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="usuario" class="form-label">Usuario (Opcional)</label>
                        <select name="usuario" id="usuario" class="form-select">
                            <option value="">Todos los usuarios</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}" {{ request('usuario') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nombre_completo }} ({{ $user->dni }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="oficina" class="form-label">Oficina (Opcional)</label>
                        <select name="oficina" id="oficina" class="form-select">
                            <option value="">Todas las oficinas</option>
                            @foreach($oficinas as $oficina)
                                <option value="{{ $oficina }}" {{ request('oficina') == $oficina ? 'selected' : '' }}>
                                    {{ $oficina }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">🔍 Generar Reporte</button>
                        <a href="{{ route('admin.reporte.html') }}" class="btn btn-secondary">🔄 Limpiar Filtros</a>
                        @if(isset($resultados) && count($resultados) > 0)
                            <button type="button" class="btn btn-success" onclick="exportarExcelReal()">📊 Exportar Excel</button>
                            <button type="button" class="btn btn-info" onclick="window.print()">🖨️ Imprimir / PDF</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    @if(isset($resultados) && count($resultados) > 0)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Resultados: {{ \DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $anio }}
                    ({{ count($resultados) }} usuarios)
                </h5>
                <small class="text-muted">Mostrando {{ count($dias_mes) }} días</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-sm mb-0" id="tablaReporte">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>DNI</th>
                                <th>Nombre Completo</th>
                                <th>Oficina</th>
                                @foreach($dias_mes as $dia)
                                    @php
                                        $esFinDeSemana = $dia->format('N') >= 6; // 6 = sábado, 7 = domingo
                                        $claseHeader = $esFinDeSemana ? 'header-fin-semana' : '';
                                    @endphp
                                    <th class="text-center {{ $claseHeader }}" style="min-width: 80px;">
                                        {{ $dia->format('d') }}<br>
                                        <small class="text-muted">{{ $dia->format('D') }}</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resultados as $fila)
                                <tr>
                                    <td class="fw-bold">{{ $fila['dni'] }}</td>
                                    <td>{{ $fila['nombre_completo'] }}</td>
                                    <td>{{ $fila['oficina'] }}</td>
                                    @foreach($fila['dias'] as $diaIndex => $dia)
                                        @php
                                            $fechaDia = $dias_mes[$diaIndex];
                                            $esFinDeSemana = $fechaDia->format('N') >= 6;
                                            $esLicencia = str_contains($dia, 'LIC:');
                                            $claseCelda = $esFinDeSemana ? 'celda-fin-semana' : '';
                                            $claseCelda .= $esLicencia ? ' celda-licencia' : '';
                                        @endphp
                                        <td class="small {{ $claseCelda }}">
                                            @if(!empty($dia))
                                                @if($esLicencia)
                                                    <div class="texto-licencia">{!! str_replace(['LIC:'], ['<strong>LIC:</strong>'], $dia) !!}</div>
                                                @else
                                                    <div class="text-success">{!! str_replace(['E:', 'S:'], ['<strong>E:</strong>', '<br><strong>S:</strong>'], $dia) !!}</div>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Leyenda de colores -->
        <div class="card mt-3 no-print">
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-md-6">
                        <small>
                            <span class="badge bg-danger me-2">🔴</span>
                            <strong>Sábados y Domingos:</strong> Días no laborables
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small>
                            <span class="badge bg-success me-2">🟢</span>
                            <strong>Licencias:</strong> Permisos aprobados
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @elseif(isset($resultados) && count($resultados) === 0)
        <div class="alert alert-warning text-center">
            📭 No se encontraron registros para los filtros seleccionados.
        </div>
    @else
        <div class="alert alert-info text-center">
            🔍 Selecciona los filtros y haz clic en "Generar Reporte" para ver los datos.
        </div>
    @endif
</div>

<style>
    @media print {
        .btn, .card-header, .alert, .no-print { display: none !important; }
        body { font-size: 12px; }
        .table { border: 1px solid #000; }
        th, td { border: 1px solid #000 !important; }
    }
    
    .table th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 10;
    }
    
    .sticky-top {
        position: sticky;
        top: 0;
    }
    
    /* Encabezados de fin de semana - Rojo más oscuro */
    .header-fin-semana {
        background-color: #ffcccc !important;
        color: #cc0000;
        font-weight: bold;
    }
    
    /* Celdas de fin de semana - Rojo muy claro */
    .celda-fin-semana {
        background-color: #fff5f5 !important;
    }
    
    /* Celdas de licencias - Verde */
    .celda-licencia {
        background-color: #f0fff0 !important;
    }
    
    /* Texto de licencias */
    .texto-licencia {
        color: #006600;
        font-weight: bold;
    }
    
    /* Asegurar que los colores se mantengan al imprimir */
    @media print {
        .header-fin-semana {
            background-color: #ffcccc !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .celda-fin-semana {
            background-color: #fff5f5 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .celda-licencia {
            background-color: #f0fff0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<!-- INCLUIR SheetJS -->
<script src="{{asset('js/plugins/xlsx-0-18-5-full.min.js')}}"></script>

<script>
function exportarExcelReal() {
    try {
        // Obtener la tabla por ID
        const tabla = document.getElementById('tablaReporte');
        
        if (!tabla) {
            alert('❌ No se encontró la tabla para exportar');
            return;
        }

        // Crear libro de Excel desde la tabla
        const wb = XLSX.utils.table_to_book(tabla, {sheet: "Reporte Asistencias"});
        
        // Generar nombre del archivo con fecha
        const fecha = new Date().toISOString().split('T')[0];
        const nombreArchivo = `reporte_asistencias_{{ $anio }}_{{ $mes }}_${fecha}.xlsx`;
        
        // Descargar archivo
        XLSX.writeFile(wb, nombreArchivo);
        
        console.log('✅ Archivo Excel exportado correctamente');
        
    } catch (error) {
        console.error('❌ Error al exportar Excel:', error);
        alert('❌ Error al exportar el archivo Excel: ' + error.message);
    }
}

// También puedes agregar un event listener para debug
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ SheetJS cargado correctamente');
    console.log('✅ Tabla disponible:', document.getElementById('tablaReporte'));
});
</script>
@endsection