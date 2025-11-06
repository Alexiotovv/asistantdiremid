<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

// PÚBLICO: Marcación sin login
Route::get('/', [AsistenciaController::class, 'marcacionPublica'])->name('marcacion.publica');
Route::post('/marcar', [AsistenciaController::class, 'marcarAsistencia'])->name('marcar.asistencia');

// Autenticación básica (sin frontend de Laravel)
Auth::routes(['verify' => false]);

// PROTEGIDAS
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reporte-asistencia', [AdminController::class, 'exportarHtml'])->name('reporte.html');
        Route::get('/gestion-usuarios', [AdminController::class, 'gestionUsuarios'])->name('gestion.usuarios');
        Route::post('/usuario/crear', [AdminController::class, 'crearUsuario'])->name('usuario.crear');
        Route::put('/usuario/{id}/editar', [AdminController::class, 'editarUsuario'])->name('usuario.editar');
        Route::delete('/usuario/{id}/eliminar', [AdminController::class, 'eliminarUsuario'])->name('usuario.eliminar');
    });
});