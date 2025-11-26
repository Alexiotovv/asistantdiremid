<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Marcar Asistencia')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
            background-color: #fff;
            color: #000;
            border: 1px solid #ccc;
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

        @keyframes pulsoError {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .mensaje-pulsar {
            animation: pulsoError 0.2s ease-in-out 2;
        }
    </style>
</head>
<body>
    @yield('content')

    <script>
        function actualizarReloj() {
            const ahora = new Date();
            document.getElementById('reloj').textContent = ahora.toLocaleTimeString('es-ES', { hour12: false });
        }
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        let inputActivo = 'dniInput';

        function setInputActivo(inputId) {
            inputActivo = inputId;
        }

        document.getElementById('dniInput').addEventListener('focus', () => {
            setInputActivo('dniInput');
        });

        document.getElementById('pinInput').addEventListener('focus', () => {
            setInputActivo('pinInput');
        });

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

                if (value === '0' || value === '1' || value === '2' || value === '3' || value === '4' || value === '5' || value === '6' || value === '7' || value === '8' || value === '9') {
                    if (inputActivo === 'dniInput') {
                        if (dniInput.value.length < 12) {
                            dniInput.value += value;
                            if (dniInput.value.length === 8) {
                                pinInput.focus();
                            }
                        }
                    } else if (inputActivo === 'pinInput') {
                        if (pinInput.value.length < 4) {
                            pinInput.value += value;
                        }
                    }
                }
            });
        });

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

            fetch('/sanctum/csrf-cookie', {
                credentials: 'include'
            })
            .then(() => {
                return fetch("{{ route('marcar.asistencia') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'include',
                    body: JSON.stringify({ dni, pin })
                });
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
            const clasePulso = (tipo === 'danger') ? 'mensaje-pulsar' : '';
            
            msgDiv.innerHTML = `
                <div class="mensaje mensaje-${tipo} ${clasePulso}">
                    ${texto}
                </div>`;
        }
    </script>
</body>
</html>