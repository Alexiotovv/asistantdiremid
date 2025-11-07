@extends('layouts.base')

@section('title') Marcar Asistencia @endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    body {
        background-color: #000;
        color: #fff;
        font-family: 'Courier New', monospace;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .terminal {
        width: 400px;
        padding: 20px;
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
        text-align: center;
    }

    #reloj {
        font-size: 2.5rem;
        font-weight: bold;
        color: #00ff00;
        background: #000;
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
    }

    .logo-container {
        margin: 20px 0;
    }

    .logo {
        width: 100px;
        height: auto;
        filter: brightness(1.5);
        border: 2px solid #00ff00;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
    }

    h2 {
        color: #00ff00;
        margin: 20px 0;
        font-size: 1.5rem;
        text-shadow: 0 0 5px #00ff00;
    }

    .input-group {
        margin: 15px 0;
    }

    .form-control {
        background-color: #fff; /* Fondo blanco */
        color: #000; /* Texto negro */
        border: 1px solid #ccc; /* Borde gris */
        border-radius: 5px;
        padding: 10px;
        font-size: 1.2rem;
        text-align: center;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.7);
        border-color: #00ff00;
    }

    #btnMarcar {
        background: linear-gradient(45deg, #00ff00, #00cc00);
        color: #000;
        border: none;
        border-radius: 5px;
        padding: 12px 20px;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        transition: all 0.3s ease;
    }

    #btnMarcar:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.7);
    }

    .teclado {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 5px;
        margin: 20px 0;
        padding: 10px;
        background: #111;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
    }

    .tecla {
        background: #222;
        color: #00ff00;
        border: 1px solid #00ff00;
        border-radius: 5px;
        padding: 10px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 0 5px rgba(0, 255, 0, 0.2);
    }

    .tecla:hover {
        background: #00ff00;
        color: #000;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
    }

    .tecla-red {
        background: #cc0000;
        border-color: #ff0000;
    }

    .tecla-green {
        background: #00cc00;
        border-color: #00ff00;
    }

    .mensaje {
        margin-top: 20px;
        padding: 10px;
        border-radius: 5px;
        font-size: 1rem;
        text-align: left;
    }

    .mensaje-success {
        background: #00cc00;
        color: #000;
    }

    .mensaje-danger {
        background: #cc0000;
        color: #fff;
    }

    /* ✅ ANIMACIÓN DE PULSO PARA MENSAJES DE ERROR */
    @keyframes pulsoError {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .mensaje-pulsar {
        animation: pulsoError 0.2s ease-in-out 2; /* 2 veces */
    }
</style>

<div class="terminal">
    <div id="reloj">--:--:--</div>

    <!-- ✅ LOGO ENTRE RELOJ Y MARCAR ASISTENCIA -->
    <div class="logo-container">
        <!-- Reemplace "logo.png" por la ruta de su imagen -->
        <img src="{{asset('images/logo_diremid.png')}}" alt="Logo" class="logo">
    </div>

    <h2>Marcar Asistencia</h2>

    <div class="input-group">
        <input type="text" id="dniInput" class="form-control" autocomplete="new-password" placeholder="Ingrese DNI" autofocus maxlength="12" name="dni_field_unique">
    </div>

    <div class="input-group">
        <!-- ✅ CAMBIO: type="text" + inputmode="numeric" + autocomplete="new-password" -->
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

<script>
    function actualizarReloj() {
        const ahora = new Date();
        document.getElementById('reloj').textContent = ahora.toLocaleTimeString('es-ES', { hour12: false });
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();

    // ✅ FUNCIONES PARA MANEJAR INPUT ACTIVO
    let inputActivo = 'dniInput';

    function setInputActivo(inputId) {
        inputActivo = inputId;
    }

    // ✅ EVENTOS PARA SABER QUÉ INPUT ESTÁ ACTIVO
    document.getElementById('dniInput').addEventListener('focus', () => {
        setInputActivo('dniInput');
    });

    document.getElementById('pinInput').addEventListener('focus', () => {
        setInputActivo('pinInput');
    });

    // ✅ TECLADO NUMÉRICO
    document.querySelectorAll('.tecla').forEach(tecla => {
        tecla.addEventListener('click', () => {
            const value = tecla.getAttribute('data-value');
            const dniInput = document.getElementById('dniInput');
            const pinInput = document.getElementById('pinInput');
            const inputActual = document.getElementById(inputActivo);

            if (value === 'ESC') {
                inputActual.value = '';
                return;
            }

            if (value === 'MENU') {
                // Cambiar entre inputs
                if (inputActivo === 'dniInput') {
                    document.getElementById('pinInput').focus();
                } else {
                    document.getElementById('dniInput').focus();
                }
                return;
            }

            if (value === 'OK') {
                marcar();
                return;
            }

            if (value === '▲' || value === '▼') {
                // Cambiar entre inputs con flechas
                if (value === '▲' && inputActivo === 'pinInput') {
                    document.getElementById('dniInput').focus();
                } else if (value === '▼' && inputActivo === 'dniInput') {
                    document.getElementById('pinInput').focus();
                }
                return;
            }

            if (value === '*') {
                inputActual.value = '';
                return;
            }

            // ✅ MANEJO DE NÚMEROS PARA AMBOS INPUTS
            if (value === '0' || value === '1' || value === '2' || value === '3' || value === '4' || value === '5' || value === '6' || value === '7' || value === '8' || value === '9') {
                if (inputActivo === 'dniInput') {
                    if (dniInput.value.length < 12) { // Máximo 12 para DNI
                        dniInput.value += value;
                        // ✅ AUTOFOCUS: Si llega a 8 dígitos, pasar al PIN
                        if (dniInput.value.length === 8) {
                            pinInput.focus();
                        }
                    }
                } else if (inputActivo === 'pinInput') {
                    if (pinInput.value.length < 4) { // Máximo 4 para PIN
                        pinInput.value += value;
                    }
                }
            }
        });
    });

    // Navegación con teclado físico
    document.getElementById('dniInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('pinInput').focus();
        }
    });

    document.getElementById('pinInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            marcar();
        }
    });

    document.getElementById('btnMarcar').addEventListener('click', marcar);

    function marcar() {
        const dni = document.getElementById('dniInput').value.trim();
        const pin = document.getElementById('pinInput').value.trim();

        if (!dni || !pin) {
            mostrarMensaje('DNI y PIN son obligatorios.', 'danger');
            return;
        }

        if (!/^\d{4}$/.test(pin)) {
            mostrarMensaje('El PIN debe ser numérico de 4 dígitos.', 'warning');
            return;
        }

        fetch("{{ route('marcar.asistencia') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ dni, pin })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje(
                    `✅ <strong>${data.nombre}</strong> - ${data.tipo === 'entrada' ? 'Entrada' : 'Salida'} marcada a las <strong>${data.hora}</strong>`,
                    'success'
                );
                document.getElementById('dniInput').value = '';
                document.getElementById('pinInput').value = '';
                document.getElementById('dniInput').focus();
            } else {
                mostrarMensaje(`❌ ${data.error}`, 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            mostrarMensaje('Error de conexión.', 'danger');
        });
    }

    function mostrarMensaje(texto, tipo) {
        const msgDiv = document.getElementById('mensaje');
        
        // ✅ AÑADIR CLASE DE PULSO SOLO PARA MENSAJES DE ERROR
        const clasePulso = (tipo === 'danger') ? 'mensaje-pulsar' : '';
        
        msgDiv.innerHTML = `
            <div class="mensaje mensaje-${tipo} ${clasePulso}">
                ${texto}
            </div>`;
    }
</script>
@endsection