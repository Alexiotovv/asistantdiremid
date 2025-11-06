@extends('layouts.base')

@section('title') Dashboard - Asistencia @endsection

@section('content')
<h2>Bienvenido, {{ auth()->user()->nombre_completo ?? auth()->user()->name }}!</h2>
<p>Estás en el panel de control del sistema de asistencia.</p>

@if(auth()->user()->role === 'admin')
    <div class="alert alert-info">
        💡 Puedes gestionar usuarios, ver reportes o acceder al panel de administración desde el menú lateral.
        <a href="{{route('admin.gestion.usuarios')}}">gestión usuarios</a>
        <a href="{{route('admin.reporte.html')}}">gestión de asistencia</a>
    </div>
@else
    <div class="alert alert-secondary">
        Solo tienes acceso limitado. Contacta al administrador para más opciones.
    </div>
@endif
@endsection