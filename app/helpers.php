<?php

use App\Models\EquipoPago;
use App\Models\Fixture;
use App\Models\Jugador;

function getCampoTipos()
{
    $array = array(
        'futbol' => 'Fútbol',
        'minifutbol' => 'Minifutbol',
        'futsal' => 'Futsal'
    );

    return $array;
}

function getTorneoEstados()
{
    $array = array(
        'pendiente' => 'Pendiente',
        'en-curso' => 'En curso',
        'cancelado' => 'Cancelado',
        'finalizado' => 'Finalizado'
    );

    return $array;
}

function getStatusClass()
{
    $array = array(
        'pendiente' => 'danger',
        'cancelado' => 'success',
        'en-curso' => 'info',
        'finalizado' => 'secondary',
        'activo' => 'info',
        'expulsado' => 'danger',
        'suplente' => 'secondary',
    );

    return $array;
}

function getTorneoHorarios()
{
    $array = array(
        '1' => '07:00 a.m.',
        '2' => '07:30 a.m.',
        '3' => '08:00 a.m.',
        '4' => '08:30 a.m.',
        '5' => '09:00 a.m.',
        '6' => '09:30 a.m.',
        '7' => '10:00 a.m.',
        '8' => '10:30 a.m.',
    );

    return $array;
}

/* Subiendo archivos al storage */
function SubirArchivo($fileName, $storageName)
{
    $imgfile = $fileName;
    //get filename with extension
    $imgname_withextension = $imgfile->getClientOriginalName();
    //get filename without extension
    $imgname = pathinfo($imgname_withextension, PATHINFO_FILENAME);
    //get file extension
    $extension = $imgfile->getClientOriginalExtension();
    //filename to store
    $imgname_tostore = str_slug($imgname).'_'.time().'.'.$extension;
    $data_adjunto =  $imgname_tostore;
    //Upload file
    $imgfile->storeAs('public/'.$storageName, $imgname_tostore);

    return $data_adjunto;
}

function getJugadorEstados()
{
    $array = array(
        'activo' => 'Activo',
        'suplente' => 'Suplente',
        'expulsado' => 'Expulsado',
        'inactivo' => 'Inactivo (fuera del torneo)'
    );

    return $array;
}

function getJugadorSexos()
{
    $array = array(
        'M' => 'Masculino',
        'F' => 'Femenino'
    );

    return $array;
}

function getFechas($torneo, $ronda)
{
    $fechas = Fixture::where('torneo_id', $torneo)->where('fecha_nro', $ronda)->get();

    return $fechas;
}


/** Ver total de jugador por equipo */
function verTotalJugadoresXEquipo($id)
{
    $nro_jugadores = Jugador::where('equipo_id', $id)->count();

    return $nro_jugadores;
}

/** Ver total saldo x torneo */
/*function verTotalSaldo($torneo_id)
{
    $totalxcobrar = EquipoPago::where('torneo_id', $torneo_id)->get()->sum('saldo');

    return $totalxcobrar;
}*/

/** Ver total cobrado x torneo */
function verTotalCobrado($torneo_id)
{
    $total_cobrado = EquipoPago::where('torneo_id', $torneo_id)->get()->sum('adelanto');

    return $total_cobrado;
}

?>
