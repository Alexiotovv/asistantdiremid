<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Marcacion;
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

        if (!checkdate($mes, 1, $anio)) return response('Fecha inválida', 400);

        // Obtener usuarios y generar datos
        $usuarios = User::where('role', 'personal')->get();
        $dias_mes = [];
        for ($d = 1; $d <= 31; $d++) {
            try {
                $dias_mes[] = new \DateTime("{$anio}-{$mes}-{$d}");
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

            foreach ($dias_mes as $dia) {
                $marcaciones = Marcacion::where('user_id', $usuario->id)
                                       ->whereDate('fecha', $dia->format('Y-m-d'))
                                       ->orderBy('hora')
                                       ->get();

                $entrada = $marcaciones->firstWhere('tipo', 'entrada');
                $salida = $marcaciones->firstWhere('tipo', 'salida');

                $texto = "";
                if ($entrada) $texto .= "E:{$entrada->hora}";
                if ($salida)  $texto .= " S:{$salida->hora}";
                // if ($entrada) $texto .= "E:{$entrada->hora->format('H:i')}";
                // if ($salida)  $texto .= " S:{$salida->hora->format('H:i')}";

                $fila['dias'][] = $texto;
            }

            $resultados[] = $fila;
        }

        return view('admin.reporte-asistencia', compact('resultados', 'dias_mes', 'mes', 'anio'));
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
        ]);

        $usuario->update([
            'dni' => $request->dni,
            'nombre_completo' => $request->nombre_completo,
            'oficina' => $request->oficina,
            'clave_pin' => $request->clave_pin,
        ]);

        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
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