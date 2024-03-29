<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TorneoController;
use App\Http\Controllers\Admin\CampoController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\JugadorController;
use App\Http\Controllers\Admin\ArbitroController;
use App\Http\Controllers\Admin\FixtureController;
use App\Http\Controllers\Admin\ExpulsionController;
use App\Http\Controllers\Admin\TablaPosicionController;
use App\Http\Controllers\Admin\JuezLineaController;
use App\Http\Controllers\Admin\ReportePagosController;


Route::get('', [HomeController::class, 'index'])->name('home');

/** Torneos */
Route::prefix('torneos')->group(function () {

    Route::get('', [TorneoController::class, 'index'])->name('torneos.index');
    Route::post('/datatable', [TorneoController::class, 'datatable']);
    Route::get('/create', [TorneoController::class, 'create'])->name('torneos.create');
    Route::post('/store-process', [TorneoController::class, 'store']);
    Route::get('/show/{ficha}', [TorneoController::class, 'show']);
    Route::get('/datatable/{ficha}', [TorneoController::class, 'listEquipos']);
    Route::get('/edit/{ficha}', [TorneoController::class, 'edit'])->name('torneos.edit');
    Route::put('/update-process', [TorneoController::class, 'update']);
    Route::post('/delete/{id}', [TorneoController::class, 'destroy']);

    /* Campos */
    Route::prefix('campos')->group(function () {

        Route::get('/datatable', [CampoController::class, 'datatable'])->name('torneos.campos.datatable');
        Route::post('/store-process', [CampoController::class, 'store']);
        Route::get('/show/{ficha}', [CampoController::class, 'show']);
        Route::put('/update-process', [CampoController::class, 'update']);
        Route::post('/delete/{id}', [CampoController::class, 'destroy']);
    });

    /** Sponsors */
    Route::prefix('sponsors')->group(function () {
        Route::get('/datatable', [SponsorController::class, 'datatable'])->name('torneos.sponsors.datatable');
        Route::post('/store-process', [SponsorController::class, 'store']);
        Route::get('/show/{ficha}', [SponsorController::class, 'show']);
        Route::put('/update-process', [SponsorController::class, 'update']);
        Route::post('/delete/{id}', [SponsorController::class, 'destroy']);
    });


});

/** Equipos */
Route::prefix('equipos')->group(function () {

    Route::get('', [EquipoController::class, 'index'])->name('equipos.index');
    Route::post('/datatable', [EquipoController::class, 'datatable']);
    Route::post('/store-process', [EquipoController::class, 'store']);
    Route::get('/show/{ficha}', [EquipoController::class, 'show']);
    Route::put('/update-process', [EquipoController::class, 'update']);
    Route::post('/delete/{id}', [EquipoController::class, 'destroy']);

    /** Pagos */
    Route::get('/pagos/{ficha}/{torneo}/datatable', [EquipoController::class, 'listPagos']);
    Route::get('/pagos/show/{ficha}', [EquipoController::class, 'showPago']);
    Route::post('/pagos/store-process', [EquipoController::class, 'storePago']);
    Route::put('/pagos/update-process', [EquipoController::class, 'updatePago']);
    Route::post('/pagos/delete/{ficha}', [EquipoController::class, 'destroyPago']);
    Route::get('/pagos/showPrecioXTorneo/{equipo}/{torneo}', [EquipoController::class, 'showPrecioXTorneo']);

    Route::prefix('jugadores')->group(function () {

        /** Jugadores */
        Route::get('/{ficha}/datatable', [JugadorController::class, 'listJugadores']);
        Route::get('/show/{ficha}', [JugadorController::class, 'show']);
        Route::post('/store-process', [JugadorController::class, 'store']);
        Route::put('/update-process', [JugadorController::class, 'update']);
        Route::post('/delete/{id}', [JugadorController::class, 'destroy']);
    });
});

/** Jugadores */
Route::prefix('jugadores')->group(function () {

    Route::get('', [JugadorController::class, 'index'])->name('jugadores.index');
    Route::get('/{ficha}/datatable', [JugadorController::class, 'listJugadores']);
    Route::get('/show/{ficha}', [JugadorController::class, 'show']);
    Route::post('/store-process', [JugadorController::class, 'store']);
    Route::put('/update-process', [JugadorController::class, 'update']);
    Route::post('/delete/{id}', [JugadorController::class, 'destroy']);
});

/** Arbitros */
Route::prefix('arbitros')->group(function () {

    Route::get('', [ArbitroController::class, 'index'])->name('arbitros.index');
    Route::get('/datatable', [ArbitroController::class, 'list']);
    Route::get('/show/{ficha}', [ArbitroController::class, 'show']);
    Route::post('/store-process', [ArbitroController::class, 'store']);
    Route::put('/update-process', [ArbitroController::class, 'update']);
    Route::post('/delete/{id}', [ArbitroController::class, 'destroy']);

});

/** Jueces de línea */
Route::prefix('jueces')->group(function () {

    Route::get('', [JuezLineaController::class, 'index'])->name('jueces.index');
    Route::get('/datatable', [JuezLineaController::class, 'list']);
    Route::get('/show/{ficha}', [JuezLineaController::class, 'show']);
    Route::post('/store-process', [JuezLineaController::class, 'store']);
    Route::put('/update-process', [JuezLineaController::class, 'update']);
    Route::post('/delete/{id}', [JuezLineaController::class, 'destroy']);

});

/** Fixtures */
Route::prefix('fixtures')->group(function () {

    Route::get('', [FixtureController::class, 'index'])->name('fixtures.index');
    Route::get('/datatable', [FixtureController::class, 'list']);
    Route::get('/generar-fixture/{ficha}', [FixtureController::class, 'generarFixture']);
    Route::post('/store-process', [FixtureController::class, 'store']);
    Route::put('/update-process', [FixtureController::class, 'update']);

    /** Administrar fixture */
    Route::prefix('administrar-fixture')->group(function () {

        Route::get('/{torneo}', [FixtureController::class, 'administrarFixture']);
        Route::get('/fechas/datatable/{torneo}/{ronda}', [FixtureController::class, 'listFechas'])->name('fixtures.datatable.fechas');
        Route::get('/show/{ficha}', [FixtureController::class, 'showEncuentro']);
        Route::get('/showJugadoresXEquipo/{equipo}', [FixtureController::class, 'showJugadoresXEquipo']);
        Route::put('/update-process', [FixtureController::class, 'updateEncuentro']);

        /** Expulsiones */
        Route::prefix('expulsiones')->group(function () {

            Route::get('/datatable/{ficha}', [ExpulsionController::class, 'list']);
            Route::post('/store-process', [ExpulsionController::class, 'store']);

        });

        /** Tabla de posiciones */
        Route::prefix('tabla-posiciones')->group(function () {

            Route::get('/generar/{ficha}', [TablaPosicionController::class, 'mostrarTablaPosiciones']);

        });
    });

});

/** Reportes de pagos */
Route::prefix('reportes')->group(function () {

    Route::get('/pagos', [ReportePagosController::class, 'index'])->name('reportes.pagos.index');
    Route::post('/pagos/datatable', [ReportePagosController::class, 'datatable'])->name('reportes.pagos.datatable');

});

?>
