<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\Admin\RutaAdminController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| WEB PÚBLICA Y MAPAS
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/mapa', [RutaController::class, 'index'])->name('mapa');
Route::get('/rutas/cercanas', [RutaController::class, 'buscarCercanas']);
Route::get('/rutas/{id}', [RutaController::class, 'show']);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // El Dashboard Principal (Decide qué mostrar según el rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil de Usuario
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

/*
|--------------------------------------------------------------------------
| ÁREA DE ADMINISTRACIÓN (Protegida por rol:administrador)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('rutas', RutaAdminController::class);
    Route::post('/admin/rutas', [RutaController::class, 'store'])->name('admin.rutas.store');
    Route::get('/reportes/nuevo', [RutaAdminController::class, 'reporte'])->name('reportes.create');
});

/*
|--------------------------------------------------------------------------
| ÁREA DE CONDUCTORES (Protegida por rol:conductor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:conductor'])->prefix('conductor')->name('conductor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';