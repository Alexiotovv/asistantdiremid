<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Marcacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class AsistenciaController extends Controller
{
    public function marcacionPublica()
    {
        return view('asistencia.marcacion-publica', ['now' => now()]);
    }

    

    public function marcarAsistencia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'dni' => 'required|string|max:12',
                'pin' => 'required|string|size:4',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => 'DNI y PIN son obligatorios.']);
            }

            $dni = $request->input('dni');
            $pin = $request->input('pin');

            if (!preg_match('/^\d{4}$/', $pin)) {
                return response()->json(['success' => false, 'error' => 'El PIN debe ser numérico de 4 dígitos.']);
            }

            $usuario = User::where('dni', $dni)
                          ->where('clave_pin', $pin)
                          ->where('role', 'personal')
                          ->first();

            if (!$usuario) {
                return response()->json(['success' => false, 'error' => 'DNI o PIN incorrectos.']);
            }

            $ahora = now();
            $hoy = $ahora->toDateString();
            $hora_actual = $ahora->toTimeString();

            $marcaciones_hoy = Marcacion::where('user_id', $usuario->id)
                                       ->where('fecha', $hoy)
                                       ->get();

            $entrada_hoy = $marcaciones_hoy->firstWhere('tipo', 'entrada');
            $salida_hoy = $marcaciones_hoy->firstWhere('tipo', 'salida');

            if ($entrada_hoy && $salida_hoy) {
                return response()->json(['success' => false, 'error' => 'Ya registró entrada y salida hoy.']);
            }

            if ($entrada_hoy) {
                Marcacion::create([
                    'user_id' => $usuario->id,
                    'fecha' => $hoy,
                    'hora' => $hora_actual,
                    'tipo' => 'salida'
                ]);
                return response()->json([
                    'success' => true,
                    'nombre' => $usuario->nombre_completo,
                    'hora' => $ahora->format('H:i:s'),
                    'tipo' => 'salida'
                ]);
            }

            Marcacion::create([
                'user_id' => $usuario->id,
                'fecha' => $hoy,
                'hora' => $hora_actual,
                'tipo' => 'entrada'
            ]);

            return response()->json([
                'success' => true,
                'nombre' => $usuario->nombre_completo,
                'hora' => $ahora->format('H:i:s'),
                'tipo' => 'entrada'
            ]);

        } catch (\Exception $e) {
            // ✅ Log del error (opcional, para debugging)
            Log::error('Error en marcar asistencia: ' . $e->getMessage());

            // ✅ Respuesta amigable al frontend
            return response()->json([
                'success' => false, 
                'error' => 'Ocurrió un error al procesar su solicitud. Intente nuevamente.'
            ], 500);
        }
    }
}