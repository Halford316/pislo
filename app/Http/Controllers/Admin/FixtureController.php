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
use App\Models\Expulsion;
use App\Models\TorneoCampo;
use App\Models\Jugador;
use Illuminate\Support\Facades\Auth;
use App\Models\JuezLinea;

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
            $torneo_fixture = Fixture::where('torneo_id', $id)->count();
            $nro_equipos_reg = EquipoPago::where('torneo_id', $id)->count();

            $muestra_status = '<span class="badge badge-'.$status_class[$ficha->status].' w-100 p-2">'.strtoupper($ficha->status).'</span>';

            $disabled_btn_fixture = ($ficha->nro_equipos === $nro_equipos_reg) ? '' : 'disabled';
            $disabled_btn_admin = ($ficha->nro_equipos === $nro_equipos_reg) ? '' : 'disabled';

            if ($ficha->fixture == '1') {
                $btn_fixture = '
                                <button onclick="administrarFixture('.$id.')" class="btn btn-secondary w-75" '.$disabled_btn_admin.'>
                                    <i class="fa fa-edit mr-2"></i>
                                    Administrar
                                </button>
                                ';
            }else {
                $btn_fixture = '
                                <button onclick="generarFixture('.$id.')" class="btn btn-danger w-75" '.$disabled_btn_fixture.'>
                                    <i class="fa fa-sitemap mr-2"></i>
                                    Generar fixture
                                </button>
                                ';
            }



            $disabled_table = ($torneo_fixture > 0) ? '' : 'disabled';

            $btn_tabla = '<button class="btn btn-secondary w-75" onclick="verTablaPosiciones('.$id.')" '.$disabled_table.'>
                            <i class="fa fa-table mr-1"></i>
                            Ver tabla
                        </button>';


            $json_response[] = array(
                "id" => $id,
                "torneo" => $ficha->nombre,
                "estado" => $muestra_status,
                "requeridos" => $ficha->nro_equipos,
                "equipos_reg" => $nro_equipos_reg,
                "tabla" => $btn_tabla,
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

                $partido_nro = 1;
                foreach ($rounds[$i] as $round) {

                    $equipo = explode(' vs ', $round);
                    $local = $equipo[0];
                    $visitante = $equipo[1];

                    $total_minutos = intval($torneo->duracion + $torneo->descanso);

                    if ($partido_nro == 1) {
                        $partido_hora = $torneo->hora_inicio;
                    }else {

                        $hora_inicio = Fixture::where('partido_nro', ($partido_nro-1))->first();
                        $partido_hora = $this->sumarMinutos($hora_inicio->partido_hora, $total_minutos);
                    }

                    /** Store fixture */
                    Fixture::create([
                        'torneo_id' => $ficha,
                        'equipo_1' => $local,
                        'equipo_2' => $visitante,
                        'fecha_nro' => $ronda,
                        'partido_nro' => $partido_nro,
                        'partido_hora' => $partido_hora,
                    ]);

                    $partido_nro++;

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

            /** Habilitando fixture a torneo */
            $torneo->fixture = 1;
            $torneo->save();

            if ($torneo) {
                return response()->json([
                    'status'=>'fixture-generated'
                ]);
            }
        }


    }

    function sumarMinutos($hora, $minutos_a_sumar) {
        // Descomponer la hora en partes: horas y minutos
        $partes = explode(':', $hora);
        $horas = (int)$partes[0];
        $minutos = (int)$partes[1];

        // Sumar los minutos
        $nuevos_minutos = $minutos + $minutos_a_sumar;

        // Calcular el exceso de minutos
        $exceso_horas = floor($nuevos_minutos / 60);
        $nuevos_minutos %= 60;

        // Sumar las horas y el exceso de horas
        $nuevas_horas = $horas + $exceso_horas;

        // Asegurarse de que las horas y los minutos tienen dos dígitos
        $nuevas_horas = str_pad($nuevas_horas, 2, '0', STR_PAD_LEFT);
        $nuevos_minutos = str_pad($nuevos_minutos, 2, '0', STR_PAD_LEFT);

        // Devolver la nueva hora en formato "00:00"
        return $nuevas_horas . ':' . $nuevos_minutos;
    }


    function convertirMinutosAHoras($minutos) {
        $horas = floor($minutos / 60);
        $minutos_restantes = $minutos % 60;

        $hora_formateada = sprintf("%02d:%02d", $horas, $minutos_restantes);

        return $hora_formateada;
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
        $jueces = JuezLinea::where('activo', 1)->get();
        $campos = TorneoCampo::where('torneo_id', $ficha->torneo_id)->get();
        $equipos = [];

        $equipos[] = array(
            $ficha->equipo_1 => $ficha->locales->nombre,
            $ficha->equipo_2 => $ficha->visitantes->nombre,
        );

        $equipo_1 = $ficha->equipo_1;
        $expulsados_local = Expulsion::whereHas('jugadores',
                                function($query) use ($equipo_1){
                                    $query->where('equipo_id', $equipo_1);
                                })->get();

        $equipo_2 = $ficha->equipo_2;
        $expulsados_visitante = Expulsion::whereHas('jugadores',
                                function($query) use ($equipo_2){
                                    $query->where('equipo_id', $equipo_2);
                                })->get();

        $jugadores_local = Jugador::where('equipo_id', $equipo_1)->get();
        $jugadores_visitante = Jugador::where('equipo_id', $equipo_2)->get();

        return response()->json([
            'ficha' => $ficha->load('locales')->load('visitantes'),
            'campos' => $campos->load('campos'),
            'arbitros' => $arbitros,
            'jueces' => $jueces,
            'equipos' => $equipos,
            'expulsados_local' => $expulsados_local->load('jugadores')->load('fixtures'),
            'expulsados_visitante' => $expulsados_visitante->load('jugadores')->load('fixtures'),
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

        $fixture_id = $data['mdl_ue_ficha_id'];

        $ficha =  Fixture::find($data['mdl_ue_ficha_id']);

        $equipo_1 = $ficha->equipo_1;
        $equipo_2 = $ficha->equipo_2;
        $expulsados_local = Expulsion::whereHas('jugadores',
                                function($query) use ($equipo_1){
                                    $query->where('equipo_id', $equipo_1);
                                })->get();

        $expulsados_visitante = Expulsion::whereHas('jugadores',
                                function($query) use ($equipo_2){
                                    $query->where('equipo_id', $equipo_2);
                                })->get();

        $expulsados = Expulsion::where('fixture_id', $fixture_id)->count();

        if ($expulsados == 0) {
            foreach ($expulsados_visitante as $visitante) {

                $jugador = Jugador::find($visitante->jugador_id);
                $jugador->status = 'activo';
                $jugador->save();
            }
        }



        $ficha->partido_fecha = $data['mdl_ue_partido_fecha'];
        $ficha->partido_hora = $data['mdl_ue_partido_hora'];
        $ficha->campo_id = $data['mdl_ue_campo'];
        $ficha->arbitro_id = $data['mdl_ue_arbitro'];
        $ficha->juez_linea_1 = $data['mdl_ue_juez_1'];
        $ficha->juez_linea_2 = $data['mdl_ue_juez_2'];
        $ficha->equipo_1_goles = $data['mdl_equipo_1_goles'];
        $ficha->equipo_2_goles = $data['mdl_equipo_2_goles'];
        $ficha->status = $data['mdl_ue_status'];
        $ficha->user_id = Auth::user()->id;
        $ficha->save();

        if ($ficha) {
            return response()->json([
                'status' => 'updated-encuentro',
                'ficha_id' => $ficha->id,
                'partido_fecha' => $ficha->partido_fecha,
                'partido_hora' => $ficha->partido_hora,
                'campo' => $ficha->campos->nombre,
                'goles_local' => $ficha->equipo_1_goles,
                'goles_visitante' => $ficha->equipo_2_goles,
                'estado' => $ficha->status
            ]);
        }
    }
}
