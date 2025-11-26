@extends('layouts.marcacion')  {{-- CAMBIO AQUÍ --}}

@section('title', 'Marcar Asistencia')

@section('content')
<div class="terminal">
    <div id="reloj">--:--:--</div>

    <!-- ✅ LOGO ENTRE RELOJ Y MARCAR ASISTENCIA -->
    <div class="logo-container">
        <img src="{{ asset('images/logo_diremid.png') }}" alt="Logo" class="logo">
    </div>

    <h2>Marcar Asistencia</h2>

    <div class="input-group">
        <input type="text" id="dniInput" class="form-control" autocomplete="new-password" placeholder="Ingrese DNI" autofocus maxlength="12" name="dni_field_unique">
    </div>

    <div class="input-group">
        <input type="text" id="pinInput" class="form-control" placeholder="Clave PIN (4 dígitos)" maxlength="4" inputmode="numeric" autocomplete="new-password" name="pin_field">
    </div>

    <!-- ✅ TECLADO NUMÉRICO COMO EN LA IMAGEN -->
    <div class="teclado">
        <div class="tecla" data-value="1">1</div>
        <div class="tecla" data-value="2">2</div>
        <div class="tecla" data-value="3">3</div>
        <div class="tecla tecla-red" data-value="ESC">ESC</div>

        <div class="tecla" data-value="4">4</div>
        <div class="tecla" data-value="5">5</div>
        <div class="tecla" data-value="6">6</div>
        <div class="tecla" data-value="MENU">MENU</div>

        <div class="tecla" data-value="7">7</div>
        <div class="tecla" data-value="8">8</div>
        <div class="tecla" data-value="9">9</div>
        <div class="tecla" data-value="▲">▲</div>

        <div class="tecla tecla-red" data-value="*">●</div>
        <div class="tecla" data-value="0">0</div>
        <div class="tecla tecla-green" data-value="OK">OK</div>
        <div class="tecla" data-value="▼">▼</div>
    </div>

    <button id="btnMarcar" class="btn btn-primary btn-lg w-100">Marcar Asistencia</button>

    <div id="mensaje" class="mt-4"></div>
</div>
@endsection