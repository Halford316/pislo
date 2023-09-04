<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JuezLinea;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JuezLineaController extends Controller
{
    public function index()
    {
        $sexos = getJugadorSexos();

        return view('admin.jueces.index', compact('sexos'));
    }

    /** Listando jueces */
    public function list()
    {
        $fichas = JuezLinea::orderBy('id', 'desc')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $muestra_foto = Storage::url('jueces_fotos/'.$ficha->foto);

            /** Calculando la edad */
            /*$fecha_nac = Carbon::parse($ficha->fecha_nac);
            $edad = $fecha_nac->diffInYears(Carbon::now());*/

            $json_response[] = array(
                "id" => $ficha->id,
                "foto" => '<img src="'.$muestra_foto.'" width="50" class="rounded">',
                "nombre" => $ficha->ape_paterno.' '.$ficha->ape_materno.', '.$ficha->nombres,
                "telefono" => $ficha->telefono,
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y'),
                "acciones" => '

                    <div class="btn-group">
                        <a href="javascript:" class="mr-3" onclick="editarJuez('.$id.')" title="Editar juez">
                            <i class="fa fa-edit"></i>
                        </a>

                        <a href="javascript:" onclick="eliminarJuez('.$id.')" title="Eliminar juez">
                            <i class="fa fa-trash"></i>
                        </a>
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
            $data_foto_adjunto = SubirArchivo($data["foto_adjunto"], 'jueces_fotos');
        }else {
            $data_foto_adjunto = '';
        }

        $ficha =  JuezLinea::create($data);
        $ficha->foto = $data_foto_adjunto;
        $ficha->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    /** Mostrando */
    public function show(JuezLinea $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }

    /** Actualizando */
    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  JuezLinea::find($data['mdl_uj_juez_id']);

        /* Subiendo foto */
        if(isset($data["mdl_uj_foto_adjunto"])) {
            Storage::disk('public')->delete('jueces_fotos/'.$ficha->foto);
            $data_foto_adjunto = SubirArchivo($data["mdl_uj_foto_adjunto"], 'jueces_fotos');
        }else {
            $data_foto_adjunto = $ficha->foto;
        }

        $ficha->ape_paterno = $data['mdl_uj_ape_paterno'];
        $ficha->ape_materno = $data['mdl_uj_ape_materno'];
        $ficha->nombres = $data['mdl_uj_nombres'];
        //$ficha->fecha_nac = $data['mdl_uj_fecha_nac'];
        $ficha->telefono = $data['mdl_uj_telefono'];
        $ficha->sexo = $data['mdl_uj_sexo'];
        $ficha->foto = $data_foto_adjunto;
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-juez']);
        }

    }

}
