<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Asesor\ApartadoController;
use App\Http\Controllers\Asesor\InicioController;
use App\Http\Controllers\Asesor\FraccionamientoController;

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por el middleware 'auth'
Route::middleware('auth')->group(function () {

    Route::get('/admin/index', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::get('/ingeniero/dashboard', function () {
        return view('ingeniero.dashboard');
    })->name('ingeniero.dashboard');
    
    Route::get('/cobranza/dashboard', function () {
        return view('cobranza.dashboard');
    })->name('cobranza.dashboard');

    Route::get('/asesor/inicio', [InicioController::class, 'index'])->name('asesor.dashboard');

    // Rutas de fraccionamiento
    Route::get('/asesor/fraccionamiento/{id}', [FraccionamientoController::class, 'show'])->name('asesor.fraccionamiento.show');
    Route::get('/asesor/fraccionamiento/{idFraccionamiento}/descargar-plano/{idPlano}', [FraccionamientoController::class, 'downloadPlano'])->name('asesor.fraccionamiento.download-plano');
    Route::get('/asesor/fraccionamiento/{idFraccionamiento}/lote/{numeroLote}', [FraccionamientoController::class, 'getLoteDetails'])->name('asesor.fraccionamiento.lote.details');
    Route::get('/asesor/fraccionamiento/{id}/descargar-plano/{planoId}', [FraccionamientoController::class, 'descargarPlano'])
    ->name('fraccionamiento.descargarPlano');


    // Lista de apartados
    Route::get('/asesor/apartados', [ApartadoController::class, 'index'])->name('asesor.apartados.index');
    // Obtener detalles vía AJAX
    Route::get('/asesor/apartados/{id}', [ApartadoController::class, 'show'])->name('asesor.apartados.show');
    // (opcional) Estadísticas si las usas
    Route::get('/asesor/apartados/estadisticas', [ApartadoController::class, 'estadisticas'])->name('asesor.apartados.estadisticas');


    
    Route::get('/asesor/perfil', function () { 
        return view('asesor.perfil'); 
    })->name('asesor.perfil');
});