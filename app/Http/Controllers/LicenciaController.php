<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $licencias = Licencia::orderBy('id')->get();
        return view('admin.licencias.index', compact('licencias'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        return view('admin.licencias.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $request->validate([
            'codigo' => 'required|string|max:10|unique:licencias,codigo',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Licencia::create($request->all());

        return redirect()->route('admin.licencias.index')
                        ->with('success', 'Licencia creada exitosamente.');
    }

    public function show(Licencia $licencia)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        return view('admin.licencias.show', compact('licencia'));
    }

    public function edit(Licencia $licencia)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        return view('admin.licencias.edit', compact('licencia'));
    }

    public function update(Request $request, Licencia $licencia)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $request->validate([
            'codigo' => 'required|string|max:10|unique:licencias,codigo,' . $licencia->id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean'
        ]);

        $licencia->update($request->all());

        return redirect()->route('admin.licencias.index')
                        ->with('success', 'Licencia actualizada exitosamente.');
    }

    public function destroy(Licencia $licencia)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $licencia->delete();

        return redirect()->route('admin.licencias.index')
                        ->with('success', 'Licencia eliminada exitosamente.');
    }

    public function toggleStatus(Licencia $licencia)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response('Acceso denegado', 403);
        }

        $licencia->update(['activo' => !$licencia->activo]);

        $status = $licencia->activo ? 'activada' : 'desactivada';
        return redirect()->back()->with('success', "Licencia {$status} exitosamente.");
    }
}