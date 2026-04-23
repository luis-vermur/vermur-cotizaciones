<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthVentasController;

// Ruta raíz — redirige según rol
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('ventas.login');
    }
    $rol = auth()->user()->rol;
    if ($rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($rol === 'pricing') {
        return redirect()->route('pricing.dashboard');
    }
    return redirect()->route('ventas.dashboard');
});

// Módulo Pricing
Route::prefix('pricing')->name('pricing.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Pricing\DashboardPricing::class)->name('dashboard');
    Route::get('/solicitud/{solicitud}', \App\Livewire\Pricing\VerSolicitud::class)->name('solicitud');
    Route::get('/cotizador/{solicitud}', \App\Livewire\Pricing\CotizadorLive::class)->name('cotizador');
});

// Módulo Ventas — sin middleware admin
Route::prefix('ventas')->name('ventas.')->group(function () {
    Route::get('/login', \App\Livewire\Ventas\Login::class)->name('login')->middleware('guest');
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Ventas\DashboardVentas::class)->name('dashboard');
        Route::get('/nueva-solicitud', \App\Livewire\Ventas\CrearSolicitud::class)->name('crear');
        Route::get('/solicitud/{solicitud}', \App\Livewire\Ventas\VerSolicitudVentas::class)->name('solicitud');
    });
});

// Módulo Supervisor
Route::prefix('supervisor')->name('supervisor.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard',        \App\Livewire\Supervisor\DashboardGeneral::class)->name('dashboard');
    Route::get('/mis-solicitudes',  \App\Livewire\Supervisor\MisSolicitudes::class)->name('mis-solicitudes');
});

// Módulo Admin
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard',   \App\Livewire\Admin\DashboardAdmin::class)->name('dashboard');
    Route::get('/solicitudes', \App\Livewire\Admin\GestionSolicitudes::class)->name('solicitudes');
    Route::get('/usuarios',    \App\Livewire\Admin\GestionUsuarios::class)->name('usuarios');
    Route::get('/clientes',    \App\Livewire\Admin\GestionClientes::class)->name('clientes');
    Route::get('/proveedores', \App\Livewire\Admin\GestionProveedores::class)->name('proveedores');
});

// Logout compartido
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('ventas.login');
})->name('logout');