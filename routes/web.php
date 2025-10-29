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

// Rutas de autenticación
Route::get('/', [InicioClientController::class, 'index'])->name('inicio');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


Route::get('/nosotros', [InicioClientController::class, 'Nosotros'])->name('nosotros');
Route::get('/contacto', [InicioClientController::class, 'Contacto'])->name('contacto');
Route::get('/mas', [InicioClientController::class, 'Mas'])->name('mas');
Route::get('/servicios', [InicioClientController::class, 'Servicios'])->name('servicios');
Route::get('/asesores', [InicioClientController::class, 'Asesores'])->name('asesores');
Route::get('/atractivos', [InicioClientController::class, 'Atractivos'])->name('atractivos');
Route::get('/mapaInteractivo', [InicioClientController::class, 'MapaInteractivo'])->name('mapa-interactivo');
Route::get('/mazunte', [InicioClientController::class, 'mazunte'])->name('mazunte');
Route::get('/salinaCruz', [InicioClientController::class, 'salinaCruz'])->name('salinaCruz');
Route::get('/tonameca', [InicioClientController::class, 'tonameca'])->name('tonameca');


// Rutas para página cliente
Route::get('/fraccionamiento/{id}', [fraccClientController::class, 'show'])
    ->name('pagina.fraccionamiento.show');

Route::get('/fraccionamiento/{id}/lote/{numero}', [fraccClientController::class, 'getLoteInfo'])
    ->name('pagina.fraccionamiento.lote.info');

Route::get('/fraccionamiento/{idFraccionamiento}/descargar-archivo/{idArchivo}', [fraccClientController::class, 'downloadArchivo'])
    ->name('pagina.fraccionamiento.download-archivo');
// ← NUEVA RUTA: Exacta para el JS (parámetros {id} y {numero})
Route::get('/fraccionamiento/{id}/lote/{numero}', [fraccClientController::class, 'getLoteDetails'])
    ->name('pagina.fraccionamiento.lote.ajax');  // Nombre único para evitar conflicto

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por el middleware 'auth'
Route::middleware('auth')->group(function () {
    Route::get('/ingeniero/dashboard', function () {
        return view('ingeniero.dashboard');
    })->name('ingeniero.dashboard');
    
 
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
    Route::get('/asesor/fraccionamiento/{idFraccionamiento}/archivo/{idArchivo}/download', [AdminFraccionamientoController::class, 'downloadArchivo'])->name('asesor.fraccionamiento.download-archivo');
  



    Route::prefix('cobranza')->name('cobranza.')->group(function () {
        Route::get('/ventas', [CobranzaVentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/{id_venta}', [CobranzaVentaController::class, 'show'])->name('ventas.show');
        Route::get('/ventas/{id_venta}/contrato', [CobranzaVentaController::class, 'generarContrato'])->name('ventas.contrato');
    });




   Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/inicio', [inicioAdminController::class, 'index'])->name('index');

        Route::get('/fraccionamiento/{id}', [AdminFraccionamientoController::class, 'show'])->name('fraccionamiento.show');
        Route::put('/fraccionamiento/{id}', [AdminFraccionamientoController::class, 'update'])->name('fraccionamiento.update');
        Route::put('/fraccionamiento/{id}/info', [AdminFraccionamientoController::class, 'updateInfo'])->name('fraccionamiento.update-info');
        Route::post('/fraccionamiento/{id}/amenidad', [AdminFraccionamientoController::class, 'addAmenidad'])->name('fraccionamiento.add-amenidad');
        Route::delete('/fraccionamiento/{id}/amenidad/{amenidadId}', [AdminFraccionamientoController::class, 'deleteAmenidad'])->name('fraccionamiento.delete-amenidad');
        Route::post('/fraccionamiento/{id}/foto', [AdminFraccionamientoController::class, 'addFoto'])->name('fraccionamiento.add-foto');
        Route::delete('/fraccionamiento/{id}/foto/{fotoId}', [AdminFraccionamientoController::class, 'deleteFoto'])->name('fraccionamiento.delete-foto');
        Route::post('/fraccionamiento/{id}/archivo', [AdminFraccionamientoController::class, 'addArchivo'])->name('fraccionamiento.add-archivo');
        Route::delete('/fraccionamiento/{id}/archivo/{archivoId}', [AdminFraccionamientoController::class, 'deleteArchivo'])->name('fraccionamiento.delete-archivo');
        // Agrega esta línea
        Route::get('/fraccionamiento/{id}/archivo/{archivoId}/download', [AdminFraccionamientoController::class, 'downloadArchivo'])->name('fraccionamiento.download-archivo');
        Route::get('/fraccionamiento/{id}/plano/{planoId}/download', [AdminFraccionamientoController::class, 'downloadPlano'])->name('fraccionamiento.download-plano');
        Route::get('/fraccionamiento/{id}/lotes', [AdminFraccionamientoController::class, 'getLotes'])->name('fraccionamiento.lotes');
        Route::get('/fraccionamiento/{id}/lote/{numeroLote}', [AdminFraccionamientoController::class, 'getLoteDetails'])->name('fraccionamiento.lote-details');
        Route::get('/fraccionamiento/{id}/geojson', [AdminFraccionamientoController::class, 'getGeoJsonConEstatus'])->name('fraccionamiento.geojson');
    
        //crear un nuevo fraccionamiento con su info
        Route::get('/fraccionamientos/create', [AdminFraccionamientoController::class, 'create'])->name('fraccionamiento.create');
        Route::post('/fraccionamientos', [AdminFraccionamientoController::class, 'store'])->name('fraccionamiento.store');
    
        //usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{id}/inactivate', [UsuarioController::class, 'inactivate'])->name('usuarios.inactivate');
        Route::patch('/usuarios/{id}/activate', [UsuarioController::class, 'activate'])->name('usuarios.activate');

        // RUTAS PARA los apartados pendientes
        Route::get('/apartados-pendientes', [AdminApartadoController::class, 'index'])->name('apartados-pendientes.index');
        Route::get('/apartados-pendientes/{id}', [AdminApartadoController::class, 'show'])->name('apartados-pendientes.show');
        Route::put('/apartados-pendientes/{id}/ticket-status', [AdminApartadoController::class, 'updateTicketStatus'])->name('apartados-pendientes.updateTicketStatus');

        // Historial de todas las ventas
        Route::get('/ventas', [AdminVentasController::class, 'index'])->name('ventas.index');
        
        // Detalle de una venta específica
        Route::get('/ventas/{id_venta}', [AdminVentasController::class, 'show'])->name('ventas.show');
        Route::get('/ventas/{id_venta}/ticket', [AdminVentasController::class, 'ticket'])->name('ventas.ticket');
        Route::put('/ventas/{id_venta}/ticket-estatus', [AdminVentasController::class, 'updateTicketEstatus'])->name('ventas.update-ticket-estatus');
        Route::put('/ventas/{id_venta}/venta-estatus', [AdminVentasController::class, 'updateVentaEstatus'])->name('ventas.update-venta-estatus');
    });
});