<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\ConductorParadaController;
use App\Http\Controllers\Admin\RutaAdminController;
use App\Http\Controllers\Admin\AuditLogController;

use App\Http\Controllers\JivoChatController;


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

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Perfil
    Route::controller(ProfileController::class)->group(function () {

        Route::get('/profile', 'edit')
            ->name('profile.edit');

        Route::patch('/profile', 'update')
            ->name('profile.update');

        Route::delete('/profile', 'destroy')
            ->name('profile.destroy');
    });
});
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/jivochat', [JivoChatController::class, 'index'])
        ->name('jivochat.index');

});
/*
|--------------------------------------------------------------------------
| ÁREA ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('rutas', RutaAdminController::class);
    
    Route::resource('conductores', ConductorController::class);

    Route::get('/reportes/nuevo', [RutaAdminController::class, 'reporte'])
        ->name('reportes.create');
    
});
Route::get('/admin/audit-logs', function () {
    return 'Audit Logs';
})->name('admin.audit-logs.index');
/*
|--------------------------------------------------------------------------
| ÁREA CONDUCTORES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:conductor'])
    ->prefix('conductor')
    ->name('conductor.')
    ->group(function () {

        // Confirmar llegada
        Route::post('/parada/{parada}/confirmar', [ConductorParadaController::class, 'confirmarLlegada'])
            ->name('parada.confirmar');

        // Registrar pasajeros
        Route::post('/parada/{parada}/pasajeros', [ConductorParadaController::class, 'registrarPasajeros'])
            ->name('parada.pasajeros');

        // Historial
        Route::get('/paradas/historial', [ConductorParadaController::class, 'historial'])
            ->name('paradas.historial');
    });

require __DIR__.'/auth.php';