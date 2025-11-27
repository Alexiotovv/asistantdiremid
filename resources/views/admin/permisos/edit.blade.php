@extends('layouts.base')

@section('title') Editar Permiso @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✏️ Editar Permiso #{{ $permiso->id }}</h2>
        <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">
            ↩️ Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.permisos.update', $permiso) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuario *</label>
                            <select name="user_id" id="user_id" class="form-select select2-usuario @error('user_id') is-invalid @enderror" required>
                                <option value="">Buscar por nombre o DNI...</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id', $permiso->user_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->nombre_completo }} ({{ $usuario->dni }}) - {{ $usuario->oficina }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="licencia_id" class="form-label">Tipo de Licencia *</label>
                            <select name="licencia_id" id="licencia_id" class="form-select @error('licencia_id') is-invalid @enderror" required>
                                <option value="">Seleccionar licencia</option>
                                @foreach($licencias as $licencia)
                                    <option value="{{ $licencia->id }}" {{ old('licencia_id', $permiso->licencia_id) == $licencia->id ? 'selected' : '' }}>
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
                                   id="fecha_inicio" name="fecha_inicio" 
                                   value="{{ old('fecha_inicio', $permiso->fecha_inicio->format('Y-m-d')) }}" required>
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha Fin *</label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   id="fecha_fin" name="fecha_fin" 
                                   value="{{ old('fecha_fin', $permiso->fecha_fin->format('Y-m-d')) }}" required>
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo *</label>
                    <textarea class="form-control @error('motivo') is-invalid @enderror" 
                              id="motivo" name="motivo" rows="3" required>{{ old('motivo', $permiso->motivo) }}</textarea>
                    @error('motivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="pendiente" {{ old('estado', $permiso->estado) == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="aprobado" {{ old('estado', $permiso->estado) == 'aprobado' ? 'selected' : '' }}>✅ Aprobado</option>
                                <option value="rechazado" {{ old('estado', $permiso->estado) == 'rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
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
                              id="observaciones" name="observaciones" rows="2">{{ old('observaciones', $permiso->observaciones) }}</textarea>
                    @error('observaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">💾 Actualizar Permiso</button>
                    <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2-usuario').select2({
            placeholder: 'Buscar por nombre o DNI...',
            allowClear: true,
            language: "es",
            width: '100%',
            theme: 'bootstrap-5'
        });
    });
</script>
@endsection