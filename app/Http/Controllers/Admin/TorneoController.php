<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Torneo;
use App\Models\TorneoCampo;
use App\Models\TorneoSponsor;
use Jenssegers\Date\Date;

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

            $muestra_status = '<span class="badge badge-'.$status_class[$ficha->status].' w-100 p-1">'.strtoupper($ficha->status).'</span>';

            $json_response[] = array(
                "id" => $id,
                "nombre" => $ficha->nombre,
                "estado" => $muestra_status,
                "equipos" => $ficha->nro_equipos,
                "equipos_reg" => '',
                "usuario" => ($ficha->user_id) ? $ficha->usuarios->name : '',
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "acciones" => '

                <div class="btn-group">
                    <button type="button" class="btn" onclick="editarIdioma('.$id.')" title="Editar">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button type="button" class="btn" onclick="eliminarIdioma('.$id.')" title="Eliminar">
                        <i class="fa fa-trash"></i>
                    </button>
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

}
