@extends('layouts.base')

@section('title') Marcar Asistencia @endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-4">
    <div id="reloj">--:--:--</div>

    <div class="text-center">
        <h2>Marcar Asistencia</h2>
        <div class="col-md-6 mx-auto">
            <input type="text" id="dniInput" class="form-control form-control-lg mb-3" autocomplete="off" placeholder="Ingrese DNI" autofocus maxlength="12">
            <input type="password" id="pinInput" class="form-control form-control-lg mb-3" placeholder="Clave PIN (4 dígitos)" maxlength="4" inputmode="numeric">
            <button id="btnMarcar" class="btn btn-primary btn-lg w-100">Marcar Asistencia</button>
        </div>
        <div id="mensaje" class="mt-4"></div>
    </div>
</div>

<script>
    function actualizarReloj() {
        const ahora = new Date();
        document.getElementById('reloj').textContent = ahora.toLocaleTimeString('es-ES', { hour12: false });
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();

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
        msgDiv.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show">
                ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
    }
</script>
@endsection