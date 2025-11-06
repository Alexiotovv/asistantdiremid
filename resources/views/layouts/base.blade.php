<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema de Asistencia')</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .content { flex-grow: 1; padding: 20px; }
        #reloj { font-size: 1.5rem; font-weight: bold; text-align: center; margin: 20px 0; color: #2c3e50; }
        .sidebar { min-width: 250px; max-width: 250px; background-color: #f8f9fa; border-right: 1px solid #dee2e6; overflow-y: auto; }
        @media (max-width: 768px) {
            .sidebar { position: absolute; left: -250px; top: 56px; height: calc(100% - 56px); z-index: 1000; }
            .sidebar.show { left: 0; }
        }
    </style>
    @yield('extra_css')
</head>
<body>

@if(auth()->check())
<nav class="navbar navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <span class="navbar-brand">Sistema de Asistencia</span>
        <div class="d-flex align-items-center">
            <span class="me-3">👤 {{ auth()->user()->nombre_completo ?? auth()->user()->name }} ({{ auth()->user()->getRoleDisplayAttribute() }})</span>
            <a href="{{ route('logout') }}" class="btn btn-outline-secondary btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>
@endif

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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('extra_js')
</body>
</html>