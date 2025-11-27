@extends('layouts.base')

@section('title') Gestión de Permisos @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Gestión de Permisos</h2>
        <a href="{{ route('admin.permisos.create') }}" class="btn btn-primary">
            ➕ Nuevo Permiso
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
                            <th>Usuario</th>
                            <th>Licencia</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Días</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permisos as $permiso)
                            <tr>
                                <td>{{ $permiso->id }}</td>
                                <td>
                                    <strong>{{ $permiso->user->nombre_completo }}</strong><br>
                                    <small class="text-muted">{{ $permiso->user->dni }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $permiso->licencia->codigo }}</span><br>
                                    <small>{{ $permiso->licencia->nombre }}</small>
                                </td>
                                <td>{{ $permiso->fecha_inicio->format('d/m/Y') }}</td>
                                <td>{{ $permiso->fecha_fin->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $permiso->dias_duracion }} días</span>
                                </td>
                                <td>
                                    @if($permiso->estado == 'aprobado')
                                        <span class="badge bg-success">✅ Aprobado</span>
                                    @elseif($permiso->estado == 'rechazado')
                                        <span class="badge bg-danger">❌ Rechazado</span>
                                    @else
                                        <span class="badge bg-warning">⏳ Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.permisos.edit', $permiso) }}" 
                                           class="btn btn-outline-primary" title="Editar">
                                            ✏️
                                        </a>
                                        
                                        @if($permiso->estado != 'aprobado')
                                            <form action="{{ route('admin.permisos.cambiar-estado', [$permiso, 'aprobado']) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" title="Aprobar">
                                                    ✅
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($permiso->estado != 'rechazado')
                                            <form action="{{ route('admin.permisos.cambiar-estado', [$permiso, 'rechazado']) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger" title="Rechazar">
                                                    ❌
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.permisos.destroy', $permiso) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este permiso?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-dark" title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    📭 No hay permisos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection