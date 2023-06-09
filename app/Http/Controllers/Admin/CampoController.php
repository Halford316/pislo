<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campo;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{

    public function datatable()
    {
        $fichas = Campo::orderBy('id', 'DESC')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $json_response[] = array(
                "id" => '<input type="checkbox" name="campo[]" id="campo_'.$id.'" class="input-chk check form-control input-xs" value="'.$id.'-'.$ficha->nombre.'" />',
                "campo" => $ficha->nombre,
                "tipo" => $ficha->tipo_campo,
                "acciones" => '
                    <div class="btn-group">
                        <button type="button" class="btn" onclick="editarCampo('.$id.')" title="Editar">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button type="button" class="btn" onclick="eliminarCampo('.$id.')" title="Eliminar">
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

    public function store(Request $request)
    {
        $data = request()->all();

        $ficha =  Campo::create($data);
        $ficha->user_id = Auth::user()->id;
        $ficha->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    public function show(Campo $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }

    /** Actualizando */
    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  Campo::find($data['mdl_uc_ficha_id']);

        $ficha->nombre = $data['mdl_uc_nombre'];
        $ficha->tipo_campo = $data['mdl_uc_tipo_campo'];
        $ficha->descripcion = $data['mdl_uc_descripcion'];
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-campo']);
        }

    }

}
