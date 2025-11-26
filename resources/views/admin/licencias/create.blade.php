@extends('layouts.base')

@section('title') Crear Licencia @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>➕ Crear Nueva Licencia</h2>
        <a href="{{ route('admin.licencias.index') }}" class="btn btn-secondary">
            ↩️ Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.licencias.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código *</label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" name="codigo" value="{{ old('codigo') }}" 
                                   placeholder="Ej: LV, LE, LG" required maxlength="10">
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Código único de 2-3 letras para identificar la licencia</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                   placeholder="Ej: Licencia Vacaciones" required maxlength="100">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" name="descripcion" rows="3" 
                              placeholder="Descripción opcional de la licencia">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">💾 Guardar Licencia</button>
                    <a href="{{ route('admin.licencias.index') }}" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection