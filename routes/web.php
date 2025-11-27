<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\PermisoController;

// PÚBLICO: Marcación sin login
Route::get('/', [AsistenciaController::class, 'marcacionPublica'])->name('marcacion.publica');
Route::post('/marcar', [AsistenciaController::class, 'marcarAsistencia'])->name('marcar.asistencia');

// Autenticación básica (sin frontend de Laravel)
// Auth::routes(['verify' => false]);
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// PROTEGIDAS
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dentro del grupo admin
        Route::prefix('permisos')->name('permisos.')->group(function () {
            Route::get('/', [PermisoController::class, 'index'])->name('index');
            Route::get('/crear', [PermisoController::class, 'create'])->name('create');
            Route::post('/', [PermisoController::class, 'store'])->name('store');
            Route::get('/{permiso}/editar', [PermisoController::class, 'edit'])->name('edit');
            Route::put('/{permiso}', [PermisoController::class, 'update'])->name('update');
            Route::delete('/{permiso}', [PermisoController::class, 'destroy'])->name('destroy');
            Route::post('/{permiso}/cambiar-estado/{estado}', [PermisoController::class, 'cambiarEstado'])->name('cambiar-estado');
        });
        
        
        //Reportes asistencias
        Route::post('/usuario/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('usuario.toggle-status');
        Route::get('/reporte-asistencia', [AdminController::class, 'exportarHtml'])->name('reporte.html');
        Route::get('/gestion-usuarios', [AdminController::class, 'gestionUsuarios'])->name('gestion.usuarios');
        Route::post('/usuario/crear', [AdminController::class, 'crearUsuario'])->name('usuario.crear');
        Route::put('/usuario/{id}/editar', [AdminController::class, 'editarUsuario'])->name('usuario.editar');
        Route::delete('/usuario/{id}/eliminar', [AdminController::class, 'eliminarUsuario'])->name('usuario.eliminar');

        // Rutas de Licencias
        Route::prefix('licencias')->name('licencias.')->group(function () {
            Route::get('/', [LicenciaController::class, 'index'])->name('index');
            Route::get('/crear', [LicenciaController::class, 'create'])->name('create');
            Route::post('/', [LicenciaController::class, 'store'])->name('store');
            Route::get('/{licencia}/editar', [LicenciaController::class, 'edit'])->name('edit');
            Route::put('/{licencia}', [LicenciaController::class, 'update'])->name('update');
            Route::delete('/{licencia}', [LicenciaController::class, 'destroy'])->name('destroy');
            Route::patch('/{licencia}/toggle-status', [LicenciaController::class, 'toggleStatus'])->name('toggle-status');
        });

        
    });
});