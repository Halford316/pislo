<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TorneoController;
use App\Http\Controllers\Admin\CampoController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\JugadorController;

Route::get('', [HomeController::class, 'index'])->name('home');

/** Torneos */
Route::prefix('torneos')->group(function () {

    Route::get('', [TorneoController::class, 'index'])->name('torneos.index');
    Route::post('/datatable', [TorneoController::class, 'datatable']);
    Route::get('/create', [TorneoController::class, 'create'])->name('torneos.create');
    Route::post('/store-process', [TorneoController::class, 'store']);

    /* Campos */
    Route::prefix('campos')->group(function () {

        Route::get('/datatable', [CampoController::class, 'datatable'])->name('torneos.campos.datatable');
        Route::post('/store-process', [CampoController::class, 'store']);
        Route::get('/show/{ficha}', [CampoController::class, 'show']);
        Route::put('/update-process', [CampoController::class, 'update']);
    });

    /** Sponsors */
    Route::prefix('sponsors')->group(function () {
        Route::get('/datatable', [SponsorController::class, 'datatable'])->name('torneos.sponsors.datatable');
        Route::post('/store-process', [SponsorController::class, 'store']);
        Route::get('/show/{ficha}', [SponsorController::class, 'show']);
        Route::put('/update-process', [SponsorController::class, 'update']);
    });


});

/** Equipos */
Route::prefix('equipos')->group(function () {

    Route::get('', [EquipoController::class, 'index'])->name('equipos.index');
    Route::post('/datatable', [EquipoController::class, 'datatable']);
    Route::post('/store-process', [EquipoController::class, 'store']);
    Route::get('/show/{ficha}', [EquipoController::class, 'show']);

    /** Pagos */
    Route::get('/pagos/{ficha}/datatable', [EquipoController::class, 'listPagos']);
    Route::get('/pagos/show/{ficha}', [EquipoController::class, 'showPago']);
    Route::post('/pagos/store-process', [EquipoController::class, 'storePago']);
    Route::put('/pagos/update-process', [EquipoController::class, 'updatePago']);
    Route::post('/pagos/delete/{ficha}', [EquipoController::class, 'destroyPago']);
    Route::get('/pagos/showPrecioXTorneo/{ficha}', [EquipoController::class, 'showPrecioXTorneo']);

    Route::prefix('jugadores')->group(function () {

        /** Jugadores */
        Route::get('/{ficha}/datatable', [JugadorController::class, 'listJugadores']);
        Route::get('/show/{ficha}', [JugadorController::class, 'show']);
        Route::post('/store-process', [JugadorController::class, 'store']);
        Route::put('/update-process', [JugadorController::class, 'update']);
    });
});

/** Jugadores */
Route::prefix('jugadores')->group(function () {

    Route::get('', [JugadorController::class, 'index'])->name('jugadores.index');
    Route::get('/{ficha}/datatable', [JugadorController::class, 'listJugadores']);
    Route::get('/show/{ficha}', [JugadorController::class, 'show']);
    Route::post('/store-process', [JugadorController::class, 'store']);
    Route::put('/update-process', [JugadorController::class, 'update']);

});


?>
