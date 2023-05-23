<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Torneo;
use App\Models\EquipoPago;
use App\Models\Fixture;
use Jenssegers\Date\Date;
use App\Models\Arbitro;
use App\Models\Campo;
use App\Models\TorneoCampo;
use App\Models\Jugador;
use Illuminate\Support\Facades\Auth;

class FixtureController extends Controller
{
    public function index()
    {
        return view('admin.fixtures.index');
    }

    public function list()
    {
        $fichas = Torneo::where('activo', '1')->get();

        $json_response = [];

        $estados = getTorneoEstados();
        $status_class = getStatusClass();

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $muestra_status = '<span class="badge badge-'.$status_class[$ficha->status].' w-100 p-1">'.strtoupper($ficha->status).'</span>';

            if ($ficha->fixture == '1') {
                $btn_fixture = '<div class="btn-group">
                                    <a href="javascript:administrarFixture('.$id.')" class="btn btn-secondary w-100">
                                        <i class="fa fa-edit mr-2"></i>
                                        Administrar
                                    </a>
                                </div>';
            }else {
                $btn_fixture = '<div class="btn-group">
                                    <a href="javascript:generarFixture('.$id.')" class="btn btn-danger">
                                        <i class="fa fa-sitemap mr-2"></i>
                                        Generar fixture
                                    </a>
                                </div>';
            }
            $json_response[] = array(
                "id" => $id,
                "torneo" => $ficha->nombre,
                "estado" => $muestra_status,
                "requeridos" => $ficha->nro_equipos,
                "equipos_reg" => '',
                "tabla" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "acciones" => $btn_fixture
            );
        }

        return response()->json([
            'data' =>  $json_response
        ]);

    }

    public function generarFixture($ficha)
    {
        $equipos = EquipoPago::where('torneo_id', $ficha)->get();
        $names = $equipos->ToArray();

        $torneo = Torneo::find($ficha);

        if ($torneo->fixture == '0') {

            //dd($names[0]['equipo_id']);

            $teams = count($equipos);

            // If odd number of teams add a "ghost".
            $ghost = false;
            if ($teams % 2 == 1) {
                $teams++;
                $ghost = true;
            }

            // Generate the fixtures using the cyclic algorithm.
            $totalRounds = $teams - 1;
            $matchesPerRound = $teams / 2;
            $rounds = array();
            for ($i = 0; $i < $totalRounds; $i++) {
                $rounds[$i] = array();
            }

            for ($round = 0; $round < $totalRounds; $round++) {
                for ($match = 0; $match < $matchesPerRound; $match++) {
                    $home = ($round + $match) % ($teams - 1);
                    $away = ($teams - 1 - $match + $round) % ($teams - 1);
                    // Last team stays in the same place while the others
                    // rotate around it.
                    if ($match == 0) {
                        $away = $teams - 1;
                    }
                    $rounds[$round][$match] = $this->team_name($home + 1, $names)
                        . " vs " . $this->team_name($away + 1, $names);
                }
            }

            for ($i = 0; $i < sizeof($rounds); $i++) {

                $ronda = ($i+1);

                foreach ($rounds[$i] as $round) {

                    $equipo = explode(' vs ', $round);
                    $local = $equipo[0];
                    $visitante = $equipo[1];

                    /** Store fixture */
                    Fixture::create([
                        'torneo_id' => $ficha,
                        'equipo_1' => $local,
                        'equipo_2' => $visitante,
                        'fecha_nro' => $ronda
                    ]);

                }
            }

            // Interleave so that home and away games are fairly evenly dispersed.
            $interleaved = array();
            for ($i = 0; $i < $totalRounds; $i++) {
                $interleaved[$i] = array();
            }

            $evn = 0;
            $odd = ($teams / 2);
            for ($i = 0; $i < sizeof($rounds); $i++) {
                if ($i % 2 == 0) {
                    $interleaved[$i] = $rounds[$evn++];
                } else {
                    $interleaved[$i] = $rounds[$odd++];
                }
            }

            $rounds = $interleaved;

            // Last team can't be away for every game so flip them
            // to home on odd rounds.
            for ($round = 0; $round < sizeof($rounds); $round++) {
                if ($round % 2 == 1) {
                    $rounds[$round][0] = $this->flip($rounds[$round][0]);
                }
            }

            // Display the fixtures
            /*for ($i = 0; $i < sizeof($rounds); $i++) {

                print "<p>Round " . ($i + 1) . "</p>\n";
                foreach ($rounds[$i] as $r) {
                    print $r . "<br />";
                }
                print "<br />";
            }*/

        }


    }

    public function team_name($num, $names) {
        $i = $num - 1;

        //dd($num);
        //dd($names[$i]['equipo_id']);
        if (sizeof($names) > $i && strlen(trim($names[$i]['equipo_id'])) > 0) {
            return trim($names[$i]['equipo_id']);
        } else {
            return $num;
        }
    }

    public function flip($match) {
        $components = explode(' vs ', $match);
        return $components[1] . " vs " . $components[0];
    }

    /** Administrar fixture */
    public function administrarFixture($torneo)
    {
        $nro_equipos = EquipoPago::where('torneo_id', $torneo)->count();
        $rondas = ($nro_equipos-1);

        //$fechas = Fixture::where('torneo_id', $torneo)->get();

        return view('admin.fixtures.administracion', compact('torneo', 'rondas'));
    }

    /** Listando fechas */
    public function listFechas($torneo, $ronda)
    {
        $fichas = getFechas($torneo, $ronda);

        $json_response = [];

        foreach($fichas as $ficha) {
            $json_response[] = array(
                "cancha" => ($ficha->campo_id) ? $ficha->campos->nombre : 'No hay cancha asignada',
                "hora" => $ficha->partido_hora,
                "equipos" => '
                        <div class="row">
                            <div class="col-4 text-right">
                                '.$ficha->locales->nombre.'
                            </div>
                            <div class="col-4 text-center">VS</div>
                            <div class="col-4">
                            '.$ficha->visitantes->nombre.'
                            </div>
                        </div>
                ',
                "acciones" => '
                        <div class="btn-group">
                            <a href="javascript:registrarFixture()" class="btn btn-danger w-100">
                                <i class="fa fa-edit mr-2"></i>
                                Registrar
                            </a>
                        </div>
                '
            );
        }

        return response()->json([
            'data' =>  $json_response
        ]);

    }

    public function showEncuentro(Fixture $ficha)
    {
        $arbitros = Arbitro::where('activo', 1)->get();
        $campos = TorneoCampo::where('torneo_id', $ficha->torneo_id)->get();
        $equipos = [];

        $equipos[] = array(
            $ficha->equipo_1 => $ficha->locales->nombre,
            $ficha->equipo_2 => $ficha->visitantes->nombre,
        );

        return response()->json([
            'ficha' => $ficha->load('locales')->load('visitantes'),
            'campos' => $campos->load('campos'),
            'arbitros' => $arbitros,
            'equipos' => $equipos
        ]);
    }

    public function showJugadoresXEquipo($equipo)
    {
        $jugadores = Jugador::where('equipo_id', $equipo)->where('status', 'activo')->get();

        return response()->json([
            'jugadores' => $jugadores
        ]);
    }

    public function updateEncuentro()
    {
        $data = request()->all();

        $ficha =  Fixture::find($data['mdl_ue_ficha_id']);

        $ficha->partido_fecha = $data['mdl_ue_partido_fecha'];
        $ficha->partido_hora = $data['mdl_ue_partido_hora'];
        $ficha->campo_id = $data['mdl_ue_campo'];
        $ficha->arbitro_id = $data['mdl_ue_arbitro'];
        $ficha->equipo_1_goles = $data['mdl_equipo_1_goles'];
        $ficha->equipo_2_goles = $data['mdl_equipo_2_goles'];
        $ficha->user_id = Auth::user()->id;
        $ficha->save();

        if ($ficha) {
            return response()->json([
                'status' => 'updated-encuentro',
                'ficha_id' => $ficha->id,
                'partido_fecha' => $ficha->partido_fecha,
                'partido_hora' => $ficha->partido_hora,
                'campo' => $ficha->campos->nombre
            ]);
        }
    }
}
