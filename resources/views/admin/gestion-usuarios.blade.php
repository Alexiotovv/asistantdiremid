@extends('layouts.base')

@section('content')
<h2>Gestión de Usuarios</h2>

{{-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif --}}

<div class="card">
    <div class="card-header">
        <h5>Crear Nuevo Usuario</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.usuario.crear') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="oficina" class="form-label">Oficina</label>
                        <input type="text" class="form-control" id="oficina" name="oficina" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="clave_pin" class="form-label">Clave PIN (4 dígitos)</label>
                        <input type="text" class="form-control" id="clave_pin" name="clave_pin" required maxlength="4">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                <label class="form-check-label" for="status">Usuario activo</label>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div>
</div>

<div class="mt-4">
    <h5>Usuarios Registrados</h5>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre Completo</th>
                    <th>Oficina</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->dni }}</td>
                    <td>{{ $usuario->nombre_completo }}</td>
                    <td>{{ $usuario->oficina }}</td>
                    <td>
                        @if($usuario->status)
                            <span class="badge bg-success">✅ Activo</span>
                        @else
                            <span class="badge bg-secondary">⏸️ Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal{{ $usuario->id }}">✏️ Editar</button>
                            
                            <!-- Botón Activar/Desactivar -->
                            <form method="POST" action="{{ route('admin.usuario.toggle-status', $usuario->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-{{ $usuario->status ? 'warning' : 'success' }}">
                                    {{ $usuario->status ? '⏸️ Desactivar' : '✅ Activar' }}
                                </button>
                            </form>

                            <!-- Botón Eliminar -->
                            <form method="POST" action="{{ route('admin.usuario.eliminar', $usuario->id) }}" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Modal de edición -->
                <div class="modal fade" id="editarModal{{ $usuario->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.usuario.editar', $usuario->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_dni_{{ $usuario->id }}" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="edit_dni_{{ $usuario->id }}" name="dni" value="{{ $usuario->dni }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_nombre_{{ $usuario->id }}" class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" id="edit_nombre_{{ $usuario->id }}" name="nombre_completo" value="{{ $usuario->nombre_completo }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_oficina_{{ $usuario->id }}" class="form-label">Oficina</label>
                                        <input type="text" class="form-control" id="edit_oficina_{{ $usuario->id }}" name="oficina" value="{{ $usuario->oficina }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_pin_{{ $usuario->id }}" class="form-label">Clave PIN (4 dígitos)</label>
                                        <input type="text" class="form-control" id="edit_pin_{{ $usuario->id }}" name="clave_pin" value="{{ $usuario->clave_pin }}" required maxlength="4">
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="edit_status_{{ $usuario->id }}" name="status" value="1" {{ $usuario->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="edit_status_{{ $usuario->id }}">Usuario activo</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection