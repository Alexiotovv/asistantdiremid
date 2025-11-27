<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Licencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermisoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $permisos = Permiso::with(['user', 'licencia'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('admin.permisos.index', compact('permisos'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $usuarios = User::where('role', 'personal')->get();
        $licencias = Licencia::activas()->get();

        return view('admin.permisos.create', compact('usuarios', 'licencias'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'licencia_id' => 'required|exists:licencias,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|max:500',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        Permiso::create($request->all());

        return redirect()->route('admin.permisos.index')
                        ->with('success', 'Permiso creado exitosamente.');
    }

    public function edit(Permiso $permiso)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $usuarios = User::where('role', 'personal')->get();
        $licencias = Licencia::activas()->get();

        return view('admin.permisos.edit', compact('permiso', 'usuarios', 'licencias'));
    }

    public function update(Request $request, Permiso $permiso)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'licencia_id' => 'required|exists:licencias,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|max:500',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $permiso->update($request->all());

        return redirect()->route('admin.permisos.index')
                        ->with('success', 'Permiso actualizado exitosamente.');
    }

    public function destroy(Permiso $permiso)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $permiso->delete();

        return redirect()->route('admin.permisos.index')
                        ->with('success', 'Permiso eliminado exitosamente.');
    }

    public function cambiarEstado(Permiso $permiso, $estado)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        if (!in_array($estado, ['aprobado', 'rechazado', 'pendiente'])) {
            return redirect()->back()->with('error', 'Estado inválido.');
        }

        $permiso->update(['estado' => $estado]);

        return redirect()->back()->with('success', "Permiso {$estado} exitosamente.");
    }
}