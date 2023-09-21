<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;

class SponsorController extends Controller
{
    public function datatable()
    {
        $fichas = Sponsor::orderBy('id', 'DESC')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $json_response[] = array(
                "id" => '<input type="checkbox" name="sponsor[]" id="sponsor_'.$id.'" class="input-chk check form-control input-xs" value="'.$id.'-'.$ficha->nombre.'" />',
                "sponsor" => $ficha->nombre,
                "descripcion" => $ficha->descripcion,
                "acciones" => '
                    <div class="btn-group">
                        <a href="javascript:" class="mr-3" onclick="editarSponsor('.$id.')" title="Editar">
                            <i class="fa fa-edit"></i>
                        </a>
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

        $ficha =  Sponsor::create($data);
        $ficha->user_id = Auth::user()->id;
        $ficha->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    public function show(Sponsor $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }

    /** Actualizando */
    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  Sponsor::find($data['mdl_us_ficha_id']);

        $ficha->nombre = $data['mdl_us_nombre'];
        $ficha->descripcion = $data['mdl_us_descripcion'];
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-sponsor']);
        }

    }

    /** Eliminando */
    public function destroy($id)
    {
        $ficha = Sponsor::findOrFail($id);

        try {
            if ($ficha->delete()) {
                return response()->json(['status'=>'deleted']);
            }

        } catch (\Exception $e) {
            dd($e);
            return "Error al eliminar";
        }
    }


}
