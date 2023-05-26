<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use Illuminate\Http\Request;

class TablaPosicionController extends Controller
{
    public function mostrarTablaPosiciones($ficha)
    {
        // Obtener los partidos de fútbol y calcular la tabla de posiciones
        $partidos = Fixture::where('torneo_id', $ficha)->where('status', 'finalizado')->get();

        // Array para almacenar las estadísticas de cada equipo
        $equipos = [];

        // Calcular las estadísticas de cada equipo
        foreach ($partidos as $partido) {
            $equipoLocal = $partido->equipo_1;
            $equipoVisitante = $partido->equipo_2;

            // Inicializar las estadísticas si el equipo no está en el array
            if (!isset($equipos[$equipoLocal])) {
                $equipos[$equipoLocal] = [
                    'played' => 0,
                    'won' => 0,
                    'tie' => 0,
                    'lost' => 0,
                    'favor' => 0,
                    'against' => 0,
                    'diff' => 0,
                    'points' => 0,
                    'team' => ''
                ];
            }

            if (!isset($equipos[$equipoVisitante])) {
                $equipos[$equipoVisitante] = [
                    //'team' => '',
                    'played' => 0,
                    'won' => 0,
                    'tie' => 0,
                    'lost' => 0,
                    'favor' => 0,
                    'against' => 0,
                    'diff' => 0,
                    'points' => 0,
                    'team' => ''
                ];
            }

            $equipos[$equipoLocal]['team'] = $partido->locales->nombre;
            $equipos[$equipoVisitante]['team'] = $partido->visitantes->nombre;

            // Actualizar las estadísticas según el resultado del partido
            $equipos[$equipoLocal]['played']++;
            $equipos[$equipoVisitante]['played']++;

            $equipos[$equipoLocal]['favor'] += $partido->equipo_1_goles;
            $equipos[$equipoLocal]['against'] += $partido->equipo_2_goles;
            $equipos[$equipoLocal]['diff'] = ($partido->equipo_1_goles - $partido->equipo_2_goles);

            $equipos[$equipoVisitante]['favor'] += $partido->equipo_2_goles;
            $equipos[$equipoVisitante]['against'] += $partido->equipo_1_goles;
            $equipos[$equipoVisitante]['diff'] = ($partido->equipo_2_goles - $partido->equipo_1_goles);

            if ($partido->equipo_1_goles > $partido->equipo_2_goles) {
                $equipos[$equipoLocal]['won']++;
                $equipos[$equipoVisitante]['lost']++;
                $equipos[$equipoLocal]['points'] += 3;

            } elseif ($partido->equipo_1_goles < $partido->equipo_2_goles) {
                $equipos[$equipoLocal]['lost']++;
                $equipos[$equipoVisitante]['won']++;
                $equipos[$equipoVisitante]['points'] += 3;
                $equipos[$equipoLocal]['diff'] = ($partido->equipo_1_goles - $partido->equipo_2_goles);
            } else {
                $equipos[$equipoLocal]['tie']++;
                $equipos[$equipoVisitante]['tie']++;
                $equipos[$equipoLocal]['points'] += 1;
                $equipos[$equipoVisitante]['points'] += 1;
            }
        }

        //dd($equipos);

        // Ordenar los equipos por puntos y diferencia de goles
        arsort($equipos);

        $tablaPosiciones = array_values($equipos);

        //dd($tablaPosiciones);

        // Retornar la tabla de posiciones como respuesta JSON
        return response()->json($tablaPosiciones);
    }

}

