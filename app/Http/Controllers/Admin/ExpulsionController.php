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
use App\Models\Expulsion;

class ExpulsionController extends Controller
{
    /** Guardando */
    public function store(Request $request)
    {
        $data = request()->all();

        $ficha = Expulsion::create([
            'torneo_id' => $data['mdl_ue_torneo_id'],
            'campo_id' => $data['mdl_ue_campo_id'],
            'fixture_id' => $data['mdl_ue_fixture_id'],
            'jugador_id' => $data['mdl_ue_jugador']
        ]);

        $nro_expulsiones = Expulsion::where('torneo_id', $data['mdl_ue_torneo_id'])->where('jugador_id', $data['mdl_ue_jugador'])->count();

        $jugador = Jugador::find($data['mdl_ue_jugador']);

        if ($nro_expulsiones > 2) {
            $jugador->status = 'inactivo';
        }else {
            $jugador->status = 'expulsado';
        }
        $jugador->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }


    public function list($ficha)
    {
        $fichas = Expulsion::where('fixture_id', $ficha)->orderBy('id', 'DESC')->get();

        if($fichas->count() > 0) {

            foreach($fichas as $ficha) {

                $jugador_nombre = $ficha->jugadores->nombres.' '.$ficha->jugadores->ape_paterno.' '.$ficha->jugadores->ape_materno;

                $json_response[] = array(

                    "equipo" => $ficha->jugadores->equipos->nombre,
                    "jugador" => $jugador_nombre,
                    "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y H:m'),
                    "actions" => '
                        <a href="javascript:" onclick="eliminar('.$ficha->id.')" title="Eliminar" id="btnEliminar">
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                        </a>
                    '
                );
            }

            return response()->json([
                'data' =>  $json_response
            ]);

        }else {
            return response()->json([
                'data' =>  ''
            ]);
        }

    }


    /** Eliminando registro */
    public function destroy($ficha)
    {
        $ficha = Expulsion::findOrFail($ficha);

        try {
            if ($ficha->delete()) {

                return response()->json(['status'=>'deleted']);
            }

        } catch (\Exception $e) {
            return "Error al eliminar";
        }

    }

}
