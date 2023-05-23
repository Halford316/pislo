<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arbitro;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ArbitroController extends Controller
{
    public function index()
    {
        $sexos = getJugadorSexos();

        return view('admin.arbitros.index', compact('sexos'));
    }

    /** Listando arbitros */
    public function list()
    {
        $fichas = Arbitro::orderBy('id', 'desc')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $muestra_foto = Storage::url('arbitro_fotos/'.$ficha->foto);

            /** Calculando la edad */
            $fecha_nac = Carbon::parse($ficha->fecha_nac);
            $edad = $fecha_nac->diffInYears(Carbon::now());

            $json_response[] = array(
                "id" => $ficha->id,
                "foto" => '<img src="'.$muestra_foto.'" width="50" class="rounded">',
                "nombre" => $ficha->ape_paterno.' '.$ficha->ape_materno.', '.$ficha->nombres,
                "edad" => $edad,
                "telefono" => $ficha->telefono,
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y'),
                "acciones" => '

                <div class="btn-group">
                    <button type="button" class="btn" onclick="editarArbitro('.$id.')" title="Editar Arbitro">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button type="button" class="btn" onclick="eliminarArbitro('.$id.')" title="Eliminar Arbitro">
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

    /** Guardando */
    public function store(Request $request)
    {
        $data = request()->all();

        /* Subiendo foto */
        if(isset($data["foto_adjunto"])) {
            $data_foto_adjunto = SubirArchivo($data["foto_adjunto"], 'arbitro_fotos');
        }else {
            $data_foto_adjunto = '';
        }

        $ficha =  Arbitro::create($data);
        $ficha->foto = $data_foto_adjunto;
        $ficha->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    /** Mostrando */
    public function show(Arbitro $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }

    /** Actualizando */
    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  Arbitro::find($data['mdl_ua_arbitro_id']);

        /* Subiendo foto */
        if(isset($data["mdl_ua_foto_adjunto"])) {
            Storage::disk('public')->delete('arbitro_fotos/'.$ficha->foto);
            $data_foto_adjunto = SubirArchivo($data["mdl_ua_foto_adjunto"], 'arbitro_fotos');
        }else {
            $data_foto_adjunto = $ficha->foto;
        }

        $ficha->ape_paterno = $data['mdl_ua_ape_paterno'];
        $ficha->ape_materno = $data['mdl_ua_ape_materno'];
        $ficha->nombres = $data['mdl_ua_nombres'];
        $ficha->fecha_nac = $data['mdl_ua_fecha_nac'];
        $ficha->telefono = $data['mdl_ua_telefono'];
        $ficha->sexo = $data['mdl_ua_sexo'];
        $ficha->foto = $data_foto_adjunto;
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-arbitro']);
        }

    }

}
