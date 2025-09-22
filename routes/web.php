<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por el middleware 'auth'
Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    Route::get('/ingeniero/dashboard', function () {
        return view('ingeniero.dashboard');
    })->name('ingeniero.dashboard');
    
    Route::get('/cobranza/dashboard', function () {
        return view('cobranza.dashboard');
    })->name('cobranza.dashboard');

    Route::get('/asesor/inicio', function () {
        return view('asesor.inicio');
    })->name('asesor.dashboard');

    Route::get('/asesor/perfil', function () {
        return view('asesor.perfil');
    })->name('asesor.perfil');
});