<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Asesor\ApartadoController;
use App\Http\Controllers\Asesor\InicioController;
use App\Http\Controllers\Asesor\FraccionamientoController;
use App\Http\Controllers\Asesor\PerfilController;
use App\Http\Controllers\Asesor\ventasController;
use App\Http\Controllers\Admin\AdminFraccionamientoController;

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por el middleware 'auth'
Route::middleware('auth')->group(function () {

    Route::get('/admin/index', function () { return view('admin.index'); })->name('admin.index');

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
    // Ruta para obtener los lotes del fraccionamiento
    Route::get('/asesor/fraccionamiento/{id}/lotes', [FraccionamientoController::class, 'getLotes'])
    ->name('asesor.fraccionamiento.lotes');
    Route::get('/geojson/lotes/{idFraccionamiento}', [FraccionamientoController::class, 'getGeoJsonConEstatus']);

    Route::get('/asesor/apartados', [ApartadoController::class, 'index'])->name('asesor.apartados.index');
    Route::get('/asesor/apartados/{id}', [ApartadoController::class, 'show'])->name('asesor.apartados.show');
    Route::get('/asesor/apartados/estadisticas', [ApartadoController::class, 'estadisticas'])->name('asesor.apartados.estadisticas');
    Route::post('/asesor/apartados', [ApartadoController::class, 'store'])->name('asesor.apartados.store');

    Route::get('/asesor/ventas', [ventasController::class, 'index'])->name('ventas.index');
    Route::get('/asesor/ventas/{id_venta}', [VentasController::class, 'show'])->name('ventas.show');
    Route::get('/ventas/crear', [VentasController::class, 'create'])->name('ventas.create');
    Route::post('/asesor/ventas', [VentasController::class, 'store'])->name('ventas.store');
    Route::patch('/ventas/{id_venta}/ticket', [ventasController::class, 'updateTicket'])->name('ventas.updateTicket');
    
    Route::get('/perfil', [PerfilController::class, 'index'])->name('asesor.perfil.index');
    Route::post('/perfil', [PerfilController::class, 'update'])->name('asesor.perfil.update');

    Route::post('/asesor/apartados/{id}/upload-ticket', [ApartadoController::class, 'uploadTicket'])->name('asesor.apartados.upload-ticket');








    

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/fraccionamiento/{id}', [AdminFraccionamientoController::class, 'show'])->name('fraccionamiento.show');
        Route::put('/fraccionamiento/{id}', [AdminFraccionamientoController::class, 'update'])->name('fraccionamiento.update');
        Route::put('/fraccionamiento/{id}/info', [AdminFraccionamientoController::class, 'updateInfo'])->name('fraccionamiento.update-info');
        Route::post('/fraccionamiento/{id}/amenidad', [AdminFraccionamientoController::class, 'addAmenidad'])->name('fraccionamiento.add-amenidad');
        Route::delete('/fraccionamiento/{id}/amenidad/{amenidadId}', [AdminFraccionamientoController::class, 'deleteAmenidad'])->name('fraccionamiento.delete-amenidad');
        Route::post('/fraccionamiento/{id}/foto', [AdminFraccionamientoController::class, 'addFoto'])->name('fraccionamiento.add-foto');
        Route::delete('/fraccionamiento/{id}/foto/{fotoId}', [AdminFraccionamientoController::class, 'deleteFoto'])->name('fraccionamiento.delete-foto');
        Route::post('/fraccionamiento/{id}/archivo', [AdminFraccionamientoController::class, 'addArchivo'])->name('fraccionamiento.add-archivo');
        Route::delete('/fraccionamiento/{id}/archivo/{archivoId}', [AdminFraccionamientoController::class, 'deleteArchivo'])->name('fraccionamiento.delete-archivo');
        Route::get('/fraccionamiento/{id}/plano/{planoId}/download', [AdminFraccionamientoController::class, 'downloadPlano'])->name('fraccionamiento.download-plano');
        Route::get('/fraccionamiento/{id}/lotes', [AdminFraccionamientoController::class, 'getLotes'])->name('fraccionamiento.lotes');
        Route::get('/fraccionamiento/{id}/lote/{numeroLote}', [AdminFraccionamientoController::class, 'getLoteDetails'])->name('fraccionamiento.lote-details');
        Route::get('/fraccionamiento/{id}/geojson', [AdminFraccionamientoController::class, 'getGeoJsonConEstatus'])->name('fraccionamiento.geojson');
    });
});


