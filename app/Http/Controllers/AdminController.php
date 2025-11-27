<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Marcacion;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsistenciasExport;


class AdminController extends Controller
{
    public function exportarHtml(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return response('Acceso denegado', 403);

        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        $usuario_id = $request->get('usuario');
        $oficina = $request->get('oficina');

        if (!checkdate($mes, 1, $anio)) {
            return redirect()->back()->with('error', 'Fecha inválida');
        }

        // Obtener usuarios con filtros
        $query = User::where('role', 'personal')->where('status', true);
        
        if ($usuario_id) {
            $query->where('id', $usuario_id);
        }
        
        if ($oficina) {
            $query->where('oficina', $oficina);
        }
        
        $usuarios = $query->get();

        // Obtener lista de oficinas para el filtro
        $oficinas = User::where('role', 'personal')
                       ->whereNotNull('oficina')
                       ->distinct()
                       ->pluck('oficina')
                       ->filter()
                       ->values();

        // Generar días del mes
        $dias_mes = [];
        for ($d = 1; $d <= 31; $d++) {
            try {
                $fecha = new \DateTime("{$anio}-{$mes}-{$d}");
                if ($fecha->format('m') != $mes) break; // Evitar días del mes siguiente
                $dias_mes[] = $fecha;
            } catch (\Exception $e) {
                break;
            }
        }

        $resultados = [];
        foreach ($usuarios as $usuario) {
            $fila = [
                'dni' => $usuario->dni,
                'nombre_completo' => $usuario->nombre_completo,
                'oficina' => $usuario->oficina,
                'dias' => [],
            ];

            // En el método exportarHtml del AdminController, modifica esta parte:
            foreach ($dias_mes as $dia) {
                $marcaciones = Marcacion::where('user_id', $usuario->id)
                                    ->whereDate('fecha', $dia->format('Y-m-d'))
                                    ->orderBy('hora')
                                    ->get();

                $entrada = $marcaciones->firstWhere('tipo', 'entrada');
                $salida = $marcaciones->firstWhere('tipo', 'salida');

                $texto = "";
                
                // Verificar si tiene permiso aprobado para esta fecha
                $permiso = Permiso::where('user_id', $usuario->id)
                                ->where('estado', 'aprobado')
                                ->whereDate('fecha_inicio', '<=', $dia->format('Y-m-d'))
                                ->whereDate('fecha_fin', '>=', $dia->format('Y-m-d'))
                                ->first();

                if ($permiso) {
                    // Si tiene permiso, mostrar la licencia
                    $texto = "LIC: {$permiso->licencia->codigo}";
                } else {
                    // Si no tiene permiso, mostrar marcaciones normales
                    if ($entrada) $texto .= "E:" . substr($entrada->hora, 0, 5);
                    if ($salida)  $texto .= $texto ? " S:" . substr($salida->hora, 0, 5) : "S:" . substr($salida->hora, 0, 5);
                }

                $fila['dias'][] = $texto;
            }

            $resultados[] = $fila;
        }

        return view('admin.reporte-filtros', compact(
            'resultados', 
            'dias_mes', 
            'mes', 
            'anio', 
            'usuarios',
            'oficinas'
        ));
    }


    public function gestionUsuarios()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return response('Acceso denegado', 403);

        $usuarios = User::where('role', 'personal')->get();
        return view('admin.gestion-usuarios', compact('usuarios'));
    }

    public function crearUsuario(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return response('Acceso denegado', 403);

        $request->validate([
            'dni' => 'required|string|max:12|unique:users,dni',
            'nombre_completo' => 'required|string|max:100',
            'oficina' => 'required|string|max:100',
            'clave_pin' => 'required|string|size:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'status' => 'sometimes|boolean' // ← Agregar validación
        ]);

        User::create([
            'name' => $request->nombre_completo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'personal',
            'dni' => $request->dni,
            'nombre_completo' => $request->nombre_completo,
            'oficina' => $request->oficina,
            'clave_pin' => $request->clave_pin,
            'status' => $request->status ?? true, // ← Agregar status
        ]);

        return redirect()->back()->with('success', 'Usuario creado exitosamente');
    }

    public function editarUsuario(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return response('Acceso denegado', 403);

        $usuario = User::findOrFail($id);

        $request->validate([
            'dni' => 'required|string|max:12|unique:users,dni,' . $id,
            'nombre_completo' => 'required|string|max:100',
            'oficina' => 'required|string|max:100',
            'clave_pin' => 'required|string|size:4',
            'status' => 'sometimes|boolean' // ← Agregar validación
        ]);

        $usuario->update([
            'dni' => $request->dni,
            'nombre_completo' => $request->nombre_completo,
            'oficina' => $request->oficina,
            'clave_pin' => $request->clave_pin,
            'status' => $request->status ?? $usuario->status, // ← Agregar status
        ]);

        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
    }

    // Agrega este nuevo método para cambiar status
    public function toggleStatus($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return response('Acceso denegado', 403);

        $usuario = User::findOrFail($id);
        $usuario->update(['status' => !$usuario->status]);

        $status = $usuario->status ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "Usuario {$status} exitosamente.");
    }

    public function eliminarUsuario($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return response('Acceso denegado', 403);

        $usuario = User::findOrFail($id);
        if ($usuario->role !== 'personal') return redirect()->back()->with('error', 'No se puede eliminar este tipo de usuario');

        $usuario->delete();
        return redirect()->back()->with('success', 'Usuario eliminado exitosamente');
    }
}