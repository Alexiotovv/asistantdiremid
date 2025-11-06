<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .wrapper { display: flex; flex: 1; overflow-y: hidden; }
        .sidebar { min-width: 250px; max-width: 250px; background-color: #f8f9fa; border-right: 1px solid #dee2e6; overflow-y: auto; }
        .content { flex-grow: 1; padding: 20px; }
        @media (max-width: 768px) {
            .sidebar { position: absolute; left: -250px; top: 56px; height: calc(100% - 56px); z-index: 1000; }
            .sidebar.show { left: 0; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="btn btn-outline-secondary d-md-none" id="toggleSidebar">☰</button>
        <a class="navbar-brand ms-2" href="{{ route('dashboard') }}">Sistema de Asistencia</a>
        <div class="d-flex ms-auto align-items-center">
            <span class="me-3">👤 {{ auth()->user()->nombre_completo ?? auth()->user()->name }} ({{ auth()->user()->getRoleDisplayAttribute() }})</span>
            <a href="{{ route('logout') }}" class="btn btn-light btn-sm">🚪 Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="wrapper">
    <div class="sidebar bg-light" id="sidebarMenu">
        <nav class="nav flex-column p-3">
            <a class="nav-link" href="{{ route('dashboard') }}">📊 Dashboard</a>

            @if(auth()->user()->role === 'admin')
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#menuReportes">
                    📊 Reportes <span>▾</span>
                </a>
                <div class="collapse ps-3" id="menuReportes">
                    <a href="{{ route('admin.reporte.html', ['mes' => now()->month, 'anio' => now()->year]) }}" class="nav-link">📥 Asistencias Mensuales</a>
                </div>

                <a class="nav-link" href="{{ route('admin.gestion.usuarios') }}">
                    👤 Gestión de Usuarios
                </a>
            @endif
        </nav>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebarMenu');
    toggleSidebar?.addEventListener('click', () => sidebar.classList.toggle('show'));
</script>
</body>
</html>