<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Sistema de Asistencia')</title>
        <!-- Bootstrap 5 CDN -->
        <link href="{{asset('css/bootstrap/bootstrap5.3.2.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/select2/select2.min.css')}}" rel="stylesheet" />
        <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
        <style>
            .select2-container--default .select2-selection--single {
                border: 1px solid #ced4da;
                height: 38px;
                padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    </style>
    <style>
        body { 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            background-color: #f8f9fa;
        }
        
        .app-wrapper {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 56px);
        }
        
        .sidebar {
            min-width: 280px;
            max-width: 280px;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            min-width: 70px;
            max-width: 70px;
        }
        
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 5px;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        .content { 
            flex-grow: 1; 
            padding: 20px;
            overflow-y: auto;
            background-color: white;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1030;
        }
        
        .nav-link {
            color: #ecf0f1 !important;
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background-color: #3498db;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            min-width: 20px;
            text-align: center;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-brand {
            font-size: 1.3rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .toggle-btn:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 56px;
                height: calc(100vh - 56px);
                z-index: 1000;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar.collapsed {
                left: -70px;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .overlay.show {
                display: block;
            }
        }
        
        /* Estilos para submenús */
        .submenu {
            margin-left: 20px;
            border-left: 2px solid rgba(255,255,255,0.2);
        }
        
        .submenu .nav-link {
            padding: 10px 15px;
            margin: 1px 5px;
            font-size: 0.9rem;
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 15px;
            margin: 15px;
            text-align: center;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.5rem;
        }
    </style>
    @yield('extra_css')
</head>
<body>

@if(auth()->check())
<nav class="navbar navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <button class="btn btn-outline-secondary d-md-none" id="toggleSidebar">
            ☰
        </button>
        <span class="navbar-brand fw-bold text-primary">🏢 Sistema de Asistencia</span>
        <div class="d-flex align-items-center">
            <span class="me-3 d-none d-sm-block">👤 {{ auth()->user()->nombre_completo ?? auth()->user()->name }} ({{ auth()->user()->role === 'admin' ? 'Administrador' : 'Personal' }})</span>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm">🚪 Salir</a>
        </div>
    </div>
</nav>

<!-- Overlay para móviles -->
<div class="overlay" id="overlay"></div>

<div class="app-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <span class="sidebar-text">🏢 Sistema Asistencia</span>
            </a>
            <button class="toggle-btn d-none d-md-block" id="toggleCollapse">
                ↕️
            </button>
        </div>

        <!-- Información del usuario -->
        <div class="user-info">
            <div class="user-avatar">
                👤
            </div>
            <div class="sidebar-text">
                <strong>{{ auth()->user()->nombre_completo ?? auth()->user()->name }}</strong>
                <br>
                <small class="text-light">
                    {{ auth()->user()->role === 'admin' ? 'Administrador' : 'Personal' }}
                </small>
            </div>
        </div>

        <!-- Menú de navegación -->
        <nav class="nav flex-column mt-3">
            <!-- Dashboard -->
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                📊 <span class="sidebar-text">Dashboard</span>
            </a>

            @if(auth()->user()->role === 'admin')
            

            <!-- Reportes de Asistencia -->
            <a class="nav-link {{ request()->routeIs('admin.reporte.html') ? 'active' : '' }}" href="{{ route('admin.reporte.html') }}">
                📈 <span class="sidebar-text">Reportes de Asistencia</span>
            </a>

            <!-- Submenú de Reportes (Opcional) -->
            <div class="submenu">
                <a class="nav-link {{ request()->get('tipo') == 'mensual' ? 'active' : '' }}" href="{{ route('admin.reporte.html', ['mes' => now()->month, 'anio' => now()->year]) }}">
                    📅 <span class="sidebar-text">Reporte Mensual</span>
                </a>
                <!-- Marcación Pública -->
                <a class="nav-link {{ request()->routeIs('marcacion.publica') ? 'active' : '' }}" href="{{ route('marcacion.publica') }}">
                    ⏰ <span class="sidebar-text">Marcar Asistencia</span>
                </a>
                
                {{-- <a class="nav-link {{ request()->get('tipo') == 'diario' ? 'active' : '' }}" href="#">
                    📋 <span class="sidebar-text">Reporte Diario</span>
                </a> --}}
            </div>
            <a class="nav-link {{ request()->routeIs('admin.permisos.*') ? 'active' : '' }}" 
                href="{{ route('admin.permisos.index') }}">
                📝 <span class="sidebar-text">Gestión de Permisos</span>
            </a>
            <!-- En el menú lateral, después de Gestión de Usuarios -->
            <a class="nav-link {{ request()->routeIs('admin.licencias.*') ? 'active' : '' }}" 
            href="{{ route('admin.licencias.index') }}">
            📑 <span class="sidebar-text">Gestión de Licencias</span>
            </a>
            <!-- Gestión de Usuarios -->
            <a class="nav-link {{ request()->routeIs('admin.gestion.usuarios') ? 'active' : '' }}" href="{{ route('admin.gestion.usuarios') }}">
                👥 <span class="sidebar-text">Gestión de Usuarios</span>
            </a>
            @endif

            <!-- Separador -->
            {{-- <div class="sidebar-header mt-4">
                <small class="text-light opacity-75">⚙️ CONFIGURACIÓN</small>
            </div> --}}

            <!-- Perfil -->
            {{-- <a class="nav-link" href="#">
                🔧 <span class="sidebar-text">Mi Perfil</span>
            </a> --}}

            <!-- Cerrar Sesión -->
            <a class="nav-link text-warning" href="{{ route('logout') }}">
                🚪 <span class="sidebar-text">Cerrar Sesión</span>
            </a>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                ❌ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>
@else
    <!-- Contenido cuando no hay usuario autenticado -->
    <div class="content">
        @yield('content')
    </div>
@endif

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle sidebar en móviles
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    
    toggleSidebar?.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    // Toggle collapse en desktop
    const toggleCollapse = document.getElementById('toggleCollapse');
    toggleCollapse?.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        
        // Cambiar el icono del botón
        if (sidebar.classList.contains('collapsed')) {
            toggleCollapse.innerHTML = '↔️';
        } else {
            toggleCollapse.innerHTML = '↕️';
        }
    });

    // Cerrar sidebar en móvil al hacer clic en un enlace
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    });

    // Cerrar sidebar al redimensionar a desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
    });
</script>

<script src="{{asset('js/plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('js/plugins/select2/i18n-es.js')}}"></script>

@yield('extra_js')

</body>
</html>