<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Asesor\ApartadoController;
use App\Http\Controllers\Asesor\InicioController;
use App\Http\Controllers\Asesor\FraccionamientoController;
use App\Http\Controllers\Asesor\PerfilController;
use App\Http\Controllers\Asesor\ventasController;
use App\Http\Controllers\Admin\AdminFraccionamientoController;
use App\Http\Controllers\Admin\AdminApartadoController;
use App\Http\Controllers\Admin\inicioAdminController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\AdminVentasController;
use App\Http\Controllers\Cobranza\CobranzaVentaController;
use App\Http\Controllers\pagina\InicioClientController;
use App\Http\Controllers\pagina\fraccClientController;
use App\Http\Controllers\Admin\AdminPromocionController;
use App\Http\Controllers\Ingeniero\IngInicioController;
use App\Http\Controllers\Ingeniero\LoteController;
use App\Http\Controllers\Ingeniero\MapasController;
use App\Http\Controllers\Auth\PasswordResetController;

// ===============================================
// RUTAS PÚBLICAS (sin autenticación)
// ===============================================

Route::get('/geojson/{nombre}.geojson', [FraccionamientoController::class, 'getGeoJsonConEstatus'])
    ->name('geojson.publico');

Route::get('/fraccionamiento/{id}/lotes', [FraccionamientoController::class, 'getLotes'])
    ->name('fraccionamiento.lotes.publico');

Route::get('/fraccionamiento/{id}/zonas', [FraccionamientoController::class, 'getZonas'])
    ->name('fraccionamiento.zonas.publico');

// Página principal y autenticación
Route::get('/', [InicioClientController::class, 'index'])->name('inicio');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Reset contraseña
Route::prefix('password')->name('password.')->group(function () {
    Route::get('/reset', [PasswordResetController::class, 'showRequestForm'])->name('request');
    Route::post('/reset/request', [PasswordResetController::class, 'sendResetCode'])
        ->name('email')
        ->middleware('throttle:6,1');
    Route::get('/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('reset');
    Route::post('/reset/{token}', [PasswordResetController::class, 'reset'])->name('update');
});

// Sitio web público
Route::view('/nosotros', 'pagina.nosotros')->name('nosotros');
Route::view('/contacto', 'pagina.contacto')->name('contacto');
Route::view('/mas', 'pagina.mas')->name('mas');
Route::view('/servicios', 'pagina.servicios')->name('servicios');
Route::get('/asesores', [InicioClientController::class, 'Asesores'])->name('asesores');
Route::view('/atractivos', 'pagina.atractivos')->name('atractivos');
Route::get('/mapaInteractivo', [InicioClientController::class, 'MapaInteractivo'])->name('mapa-interactivo');
Route::get('/mazunte', [InicioClientController::class, 'mazunte'])->name('mazunte');
Route::get('/salinaCruz', [InicioClientController::class, 'salinaCruz'])->name('salinaCruz');
Route::get('/tonameca', [InicioClientController::class, 'tonameca'])->name('tonameca');

// Página cliente (pública)
Route::get('/fraccionamiento/{id}', [fraccClientController::class, 'show'])->name('pagina.fraccionamiento.show');
Route::get('/fraccionamiento/{id}/lote/{numero}', [fraccClientController::class, 'getLoteInfo'])
    ->name('pagina.fraccionamiento.lote.info');
Route::get('/fraccionamiento/{idFraccionamiento}/descargar-archivo/{idArchivo}', [fraccClientController::class, 'downloadArchivo'])
    ->name('pagina.fraccionamiento.download-archivo');
Route::get('/fraccionamiento/{id}/lote/{numero}', [fraccClientController::class, 'getLoteDetails'])
    ->name('pagina.fraccionamiento.lote.ajax');


// ===============================================
// RUTAS AUTENTICADAS
// ===============================================

Route::middleware('auth')->group(function () {

    // ===================================================================
    // RUTAS PARA ASESOR (tipo_usuario 2 y 3) + ADMINISTRADOR (1)
    // ===================================================================
    Route::middleware('asesor')->group(function () {

        Route::get('/asesor/inicio', [InicioController::class, 'index'])->name('asesor.dashboard');

        // Fraccionamientos - Asesor
        Route::prefix('asesor/fraccionamiento')->name('asesor.fraccionamiento.')->group(function () {
            Route::get('/{id}', [FraccionamientoController::class, 'show'])->name('show');
            Route::get('/{idFraccionamiento}/descargar-plano/{idPlano}', [FraccionamientoController::class, 'downloadPlano'])
                ->name('download-plano');
            Route::get('/{idFraccionamiento}/lote/{numeroLote}', [FraccionamientoController::class, 'getLoteDetails'])
                ->name('lote.details');
            Route::get('/{id}/lotes', [FraccionamientoController::class, 'getLotes'])->name('lotes');
            Route::get('/{id}/zonas', [FraccionamientoController::class, 'getZonas']);
            Route::get('/{idFraccionamiento}/archivo/{idArchivo}/download', [AdminFraccionamientoController::class, 'downloadArchivo'])
                ->name('download-archivo');
        });

        Route::get('/geojson/lotes/{idFraccionamiento}', [FraccionamientoController::class, 'getGeoJsonConEstatus']);

        // Apartados
        //Route::resource('asesor/apartados', ApartadoController::class)->only(['index', 'show']);
        Route::get('/asesor/apartados', [ApartadoController::class, 'index'])->name('asesor.apartados.index');
        Route::get('/asesor/apartados/{id}', [ApartadoController::class, 'show'])->name('asesor.apartados.show');
        Route::get('/asesor/apartados/estadisticas', [ApartadoController::class, 'estadisticas'])->name('asesor.apartados.estadisticas');
        Route::post('/asesor/apartados', [ApartadoController::class, 'store'])->name('asesor.apartados.store');
        Route::post('/asesor/apartados/{id}/upload-ticket', [ApartadoController::class, 'uploadTicket'])->name('asesor.apartados.upload-ticket');

        // Ventas (Asesor)
        Route::get('/asesor/ventas', [ventasController::class, 'index'])->name('ventas.index');
        Route::get('/asesor/ventas/{id_venta}', [ventasController::class, 'show'])->name('ventas.show');
        Route::get('/ventas/crear', [ventasController::class, 'create'])->name('ventas.create');
        Route::post('/asesor/ventas', [ventasController::class, 'store'])->name('ventas.store');
        Route::patch('/ventas/{id_venta}/ticket', [ventasController::class, 'updateTicket'])->name('ventas.updateTicket');

        // Ventas directas
        Route::get('/ventas/directa/crear', [ventasController::class, 'createDirect'])->name('ventas.directa.crear');
        Route::post('/ventas/directa', [ventasController::class, 'storeDirect'])->name('ventas.direct.store');

        // Cobranza (¿la usa el asesor también? Sí, según tu código original)
        Route::prefix('cobranza')->name('cobranza.')->group(function () {
            Route::get('/ventas', [CobranzaVentaController::class, 'index'])->name('ventas.index');
            Route::get('/ventas/{id_venta}', [CobranzaVentaController::class, 'show'])->name('ventas.show');
            Route::get('/ventas/{id_venta}/contrato', [CobranzaVentaController::class, 'generarContrato'])->name('ventas.contrato');
        });

        // Perfil
        Route::get('/perfil', [PerfilController::class, 'index'])->name('asesor.perfil.index');
        Route::post('/perfil', [PerfilController::class, 'update'])->name('asesor.perfil.update');
    });

    // ===================================================================
    // RUTAS SOLO PARA ADMINISTRADOR (tipo_usuario = 1)
    // ===================================================================
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/inicio', [inicioAdminController::class, 'index'])->name('index');

        // Fraccionamientos Admin
        Route::get('/fraccionamiento/{id}', [AdminFraccionamientoController::class, 'show'])->name('fraccionamiento.show');
        Route::put('/fraccionamiento/{id}', [AdminFraccionamientoController::class, 'update'])->name('fraccionamiento.update');
        Route::put('/fraccionamiento/{id}/info', [AdminFraccionamientoController::class, 'updateInfo'])->name('fraccionamiento.update-info');

        Route::post('/fraccionamiento/{id}/amenidad', [AdminFraccionamientoController::class, 'addAmenidad'])->name('fraccionamiento.add-amenidad');
        Route::delete('/fraccionamiento/{id}/amenidad/{amenidadId}', [AdminFraccionamientoController::class, 'deleteAmenidad'])->name('fraccionamiento.delete-amenidad');
        Route::post('/fraccionamiento/{id}/foto', [AdminFraccionamientoController::class, 'addFoto'])->name('fraccionamiento.add-foto');
        Route::delete('/fraccionamiento/{id}/foto/{fotoId}', [AdminFraccionamientoController::class, 'deleteFoto'])->name('fraccionamiento.delete-foto');
        Route::post('/fraccionamiento/{id}/archivo', [AdminFraccionamientoController::class, 'addArchivo'])->name('fraccionamiento.add-archivo');
        Route::delete('/fraccionamiento/{id}/archivo/{archivoId}', [AdminFraccionamientoController::class, 'deleteArchivo'])->name('fraccionamiento.delete-archivo');
        Route::get('/fraccionamiento/{id}/archivo/{archivoId}/download', [AdminFraccionamientoController::class, 'downloadArchivo'])
            ->name('fraccionamiento.download-archivo');
        Route::get('/fraccionamiento/{id}/plano/{planoId}/download', [AdminFraccionamientoController::class, 'downloadPlano'])
            ->name('fraccionamiento.download-plano');

        Route::get('/fraccionamiento/{id}/lotes', [AdminFraccionamientoController::class, 'getLotes'])->name('fraccionamiento.lotes');
        Route::get('/fraccionamiento/{id}/lote/{numeroLote}', [AdminFraccionamientoController::class, 'getLoteDetails'])
            ->name('fraccionamiento.lote-details');
        Route::get('/fraccionamiento/{id}/geojson', [AdminFraccionamientoController::class, 'getGeoJsonConEstatus'])
            ->name('fraccionamiento.geojson');

        // Crear fraccionamiento
        Route::get('/fraccionamientos/create', [AdminFraccionamientoController::class, 'create'])->name('fraccionamiento.create');
        Route::post('/fraccionamientos', [AdminFraccionamientoController::class, 'store'])->name('fraccionamiento.store');

        // Usuarios
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
        Route::patch('/usuarios/{id}/inactivate', [UsuarioController::class, 'inactivate'])->name('usuarios.inactivate');
        Route::patch('/usuarios/{id}/activate', [UsuarioController::class, 'activate'])->name('usuarios.activate');

        // Apartados pendientes
        Route::get('/apartados-pendientes', [AdminApartadoController::class, 'index'])->name('apartados-pendientes.index');
        Route::get('/apartados-pendientes/{id}', [AdminApartadoController::class, 'show'])->name('apartados-pendientes.show');
        Route::put('/apartados-pendientes/{id}/ticket-status', [AdminApartadoController::class, 'updateTicketStatus'])
            ->name('apartados-pendientes.updateTicketStatus');

        // Ventas Admin
        Route::get('/ventas', [AdminVentasController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/{id_venta}', [AdminVentasController::class, 'show'])->name('ventas.show');
        Route::get('/ventas/{id_venta}/ticket', [AdminVentasController::class, 'ticket'])->name('ventas.ticket');
        Route::put('/ventas/{id_venta}/ticket-estatus', [AdminVentasController::class, 'updateTicketEstatus'])->name('ventas.update-ticket-estatus');
        Route::put('/ventas/{id_venta}/venta-estatus', [AdminVentasController::class, 'updateVentaEstatus'])->name('ventas.update-venta-estatus');

        // Promociones
        Route::prefix('promociones')->name('promociones.')->group(function () {
            Route::post('/', [AdminPromocionController::class, 'store'])->name('store');
            Route::put('/{promocion}', [AdminPromocionController::class, 'update'])->name('update');
            Route::delete('/{promocion}', [AdminPromocionController::class, 'destroy'])->name('destroy');
        });

        // Zonas
        Route::put('/fraccionamiento/{id}/zona/{zonaId}', [AdminFraccionamientoController::class, 'updateZona'])
            ->name('fraccionamiento.update-zona');
        Route::post('/fraccionamiento/{id}/asignar-lotes-zona', [AdminFraccionamientoController::class, 'asignarLotesAZona'])
            ->name('fraccionamiento.asignar-lotes-zona');
    });

    // ===================================================================
    // RUTAS SOLO PARA INGENIERO (tipo_usuario = 4)
    // ===================================================================
       // RUTAS SOLO PARA INGENIERO (tipo_usuario = 4)
    Route::middleware('ingeniero')->prefix('ing')->name('ing.')->group(function () {

        // Dashboard del ingeniero
        Route::get('/inicio', [IngInicioController::class, 'index'])->name('inicio');

        // Selección de fraccionamiento para editar mapas
        Route::get('/mapas-fraccionamientos', [MapasController::class, 'index'])
            ->name('mapa-fraccionamientos');

        // Obtener datos GeoJSON del fraccionamiento seleccionado
        Route::get('/fraccionamiento/{id}/geojson-data', [MapasController::class, 'getGeoJSONData'])
            ->name('fraccionamiento.geojson-data');

        // Guardar cambios en el GeoJSON
        Route::post('/fraccionamiento/save-geojson', [MapasController::class, 'saveGeoJSON'])
            ->name('fraccionamiento.save-geojson');

        // Gestión completa de lotes (CRUD + importación)
        Route::prefix('fraccionamientos/{id_fraccionamiento}/lotes')
            ->name('lotes.')
            ->controller(LoteController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id_lote}', 'update')->name('update');
                Route::delete('/{id_lote}', 'destroy')->name('destroy');
                Route::post('/bulk-delete', 'bulkDelete')->name('bulkDelete');
                Route::post('/importar', 'importarCsv')->name('importar');
                Route::get('/csv-ejemplo', 'csvEjemplo')->name('csv.example');
            });
    });
});
