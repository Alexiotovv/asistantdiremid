@extends('layouts.base')

@section('title') Gestión de Licencias @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Gestión de Licencias</h2>
        <a href="{{ route('admin.licencias.create') }}" class="btn btn-primary">
            ➕ Nueva Licencia
        </a>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($licencias as $licencia)
                            <tr>
                                <td>{{ $licencia->id }}</td>
                                <td><strong>{{ $licencia->codigo }}</strong></td>
                                <td>{{ $licencia->nombre }}</td>
                                <td>{{ $licencia->descripcion ?? '--' }}</td>
                                <td>
                                    <span class="badge bg-{{ $licencia->activo ? 'success' : 'secondary' }}">
                                        {{ $licencia->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.licencias.edit', $licencia) }}" 
                                           class="btn btn-outline-primary" title="Editar">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.licencias.toggle-status', $licencia) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-{{ $licencia->activo ? 'warning' : 'success' }}"
                                                    title="{{ $licencia->activo ? 'Desactivar' : 'Activar' }}">
                                                {{ $licencia->activo ? '⏸️' : '▶️' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.licencias.destroy', $licencia) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta licencia?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    📭 No hay licencias registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leyenda de Licencias -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            📖 Leyenda de Licencias
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>🏖️ <strong>LV</strong> - Licencia Vacaciones (L. Personal)</li>
                        <li>🏥 <strong>LE</strong> - Licencia Enfermedad</li>
                        <li>🤰 <strong>LG</strong> - Licencia Gravidez (Embarazo)</li>
                        <li>📝 <strong>LP</strong> - Licencia Particular (Sin GOCE de Haber)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>🎂 <strong>LO</strong> - Licencia por Onomástico</li>
                        <li>👨‍👦 <strong>LPT</strong> - Licencia por Paternidad</li>
                        <li>👩‍👦 <strong>LMT</strong> - Licencia por Maternidad</li>
                        <li>⚰️ <strong>LF</strong> - Licencias por Fallecimiento</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection