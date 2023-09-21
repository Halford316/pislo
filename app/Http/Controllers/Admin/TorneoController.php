<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Torneo;
use App\Models\TorneoCampo;
use App\Models\TorneoSponsor;
use Jenssegers\Date\Date;
use App\Models\EquipoPago;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\RouteBinding;

class TorneoController extends Controller
{
    public function index()
    {
        return view('admin.torneos.index');
    }

    public function datatable()
    {
        $fichas = Torneo::where('activo', '1')->get();

        $json_response = [];

        $estados = getTorneoEstados();
        $status_class = getStatusClass();

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $muestra_status = '<span class="badge badge-'.$status_class[$ficha->status].' w-100 p-2">'.strtoupper($ficha->status).'</span>';
            $muestra_equipos = '<a href="javascript:" onclick="verEquipos('.$id.');" class="btn btn-secondary w-75">Ver lista</a>';

            $ver_total_cobrado = verTotalCobrado($id);
            $ver_total_x_cobrar = $ficha->precio * $ficha->nro_equipos;
            $ver_total_restante = $ver_total_x_cobrar - $ver_total_cobrado;

            $json_response[] = array(
                "id" => $id,
                "nombre" => $ficha->nombre,
                "estado" => $muestra_status,
                "equipos" => $ficha->nro_equipos,
                "costo" => $ficha->precio,
                "txc" => $ver_total_x_cobrar,
                "tc" => $ver_total_cobrado,
                "tr" => $ver_total_restante,
                "equipos_reg" => $muestra_equipos,
                "usuario" => ($ficha->user_id) ? $ficha->usuarios->name : '',
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "acciones" => '

                <div class="btn-group">
                    <a href="'.route('torneos.edit', $id).'" onclick="editarTorneo('.$id.')" title="Editar" class="mr-3">
                        <i class="ico-edicion fa-lg"></i>
                    </a>

                    <a href="javascript:" onclick="eliminar('.$id.')" title="Eliminar">
                        <i class="ico-eliminar fa-lg"></i>
                    </a>
                </div>
                '
            );
        }

        return response()->json([
            'data' =>  $json_response
        ]);

    }


    public function create()
    {
        $horarios = getTorneoHorarios();
        $campo_tipos = getCampoTipos();

        return view('admin.torneos.create', compact('horarios', 'campo_tipos'));
    }

    public function store(Request $request)
    {
        $data = request()->all();

        if ($data['campo_existe'] > 0) {

            $total_campos = count($data['campos']);

            $ficha = Torneo::create($data);

            if ($ficha) {

                /** Guardando campos */
                for($i=0; $i<$total_campos; $i++) {

                    TorneoCampo::create([
                        'torneo_id' => $ficha->id,
                        'campo_id' => $data['campos'][$i],
                        'user_id' => $data['user_id']
                    ]);

                }

                if ($data['sponsor_existe'] > 0) {
                    $total_sponsors = count($data['sponsors']);

                    /** Guardando sponsors */
                    for($i=0; $i<$total_sponsors; $i++) {

                        TorneoSponsor::create([
                            'torneo_id' => $ficha->id,
                            'sponsor_id' => $data['sponsors'][$i],
                            'user_id' => $data['user_id']
                        ]);

                    }
                }

                return response()->json(['success'=>'Data is successfully added']);
            }
        }else {
            return response()->json(['status'=>'no-hay-campos']);
        }

    }

    public function show(Torneo $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }


    public function listEquipos($id)
    {
        $fichas = EquipoPago::where('torneo_id', $id)->orderBy('id', 'desc')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;
            $nro_jugadores = verTotalJugadoresXEquipo($ficha->equipos->id);

            $json_response[] = array(
                "id" => $id,
                "nombre" => $ficha->equipos->nombre,
                "nro_jugadores" => $nro_jugadores,
                "status" => $ficha->status,
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y')
            );
        }

        return response()->json([
            'data' =>  $json_response
        ]);

    }

    public function edit($id)
    {
        $ficha = Torneo::find($id);
        $horarios = getTorneoHorarios();
        $campo_tipos = getCampoTipos();

        return view('admin.torneos.edit', compact('ficha', 'horarios', 'campo_tipos'));

    }

    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  Torneo::find($data['torneo_id']);

        $ficha->nombre = $data['nombre'];
        $ficha->fecha_inicio = $data['fecha_inicio'];
        $ficha->premio = $data['premio'];
        $ficha->nro_equipos = $data['nro_equipos'];
        $ficha->precio = $data['precio'];
        $ficha->direccion = $data['direccion'];
        $ficha->lugar = $data['lugar'];
        $ficha->duracion = $data['duracion'];
        $ficha->descanso = $data['descanso'];
        $ficha->hora_inicio = $data['hora_inicio'];
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-torneo']);
        }

    }

    /** Eliminando */
    public function destroy($id)
    {
        $ficha = Torneo::findOrFail($id);

        try {
            $campos = TorneoCampo::where('torneo_id', $id);
            $sponsors = TorneoSponsor::where('torneo_id', $id);

            $campos->delete();
            $sponsors->delete();

            if ($ficha->delete()) {
                return response()->json(['status'=>'deleted']);
            }

        } catch (\Exception $e) {
            dd($e);
            return "Error al eliminar";
        }
    }



}
