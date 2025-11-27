@extends('layouts.base')

@section('title') Crear Permiso @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>➕ Crear Nuevo Permiso</h2>
        <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">
            ↩️ Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.permisos.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuario *</label>
                            <select name="user_id" id="user_id" class="form-select select2-usuario @error('user_id') is-invalid @enderror" required>
                                <option value="">Buscar por nombre o DNI...</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->nombre_completo }} ({{ $usuario->dni }}) - {{ $usuario->oficina }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Escribe el nombre o DNI del empleado para buscar</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="licencia_id" class="form-label">Tipo de Licencia *</label>
                            <select name="licencia_id" id="licencia_id" class="form-select @error('licencia_id') is-invalid @enderror" required>
                                <option value="">Seleccionar licencia</option>
                                @foreach($licencias as $licencia)
                                    <option value="{{ $licencia->id }}" {{ old('licencia_id') == $licencia->id ? 'selected' : '' }}>
                                        {{ $licencia->codigo }} - {{ $licencia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('licencia_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio *</label>
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                   id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha Fin *</label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo *</label>
                    <textarea class="form-control @error('motivo') is-invalid @enderror" 
                              id="motivo" name="motivo" rows="3" 
                              placeholder="Describa el motivo del permiso..." required>{{ old('motivo') }}</textarea>
                    @error('motivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="aprobado" {{ old('estado') == 'aprobado' ? 'selected' : '' }}>✅ Aprobado</option>
                                <option value="rechazado" {{ old('estado') == 'rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                              id="observaciones" name="observaciones" rows="2"
                              placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">💾 Guardar Permiso</button>
                    <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    /* Estilos personalizados para Select2 */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 5px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 8px;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #ced4da;
    }
</style>
@endsection

@section('extra_js')
    <script>
    // Inicializar Select2 para el campo de usuario
    $(document).ready(function() {
        $('.select2-usuario').select2({
            placeholder: 'Buscar por nombre o DNI...',
            allowClear: true,
            language: "es",
            width: '100%',
            theme: 'bootstrap-5',
            matcher: function(params, data) {
                // Si el término de búsqueda está vacío, devuelve todos los resultados
                if ($.trim(params.term) === '') {
                    return data;
                }
                
                // Normalizar términos
                var term = params.term.toLowerCase();
                var text = data.text.toLowerCase();
                
                // Buscar en el texto completo (nombre, dni, oficina)
                if (text.indexOf(term) > -1) {
                    return data;
                }
                
                // Si no coincide, devolver null
                return null;
            }
        });

        // Calcular días automáticamente
        document.getElementById('fecha_inicio').addEventListener('change', calcularDias);
        document.getElementById('fecha_fin').addEventListener('change', calcularDias);

        function calcularDias() {
            const inicio = document.getElementById('fecha_inicio').value;
            const fin = document.getElementById('fecha_fin').value;
            
            if (inicio && fin) {
                const diffTime = Math.abs(new Date(fin) - new Date(inicio));
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                // Mostrar alerta si son muchos días
                if (diffDays > 30) {
                    alert(`⚠️ Atención: El permiso es por ${diffDays} días.`);
                }
            }
        }
    });
</script>
@endsection