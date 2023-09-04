<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\EquipoPago;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JugadorController extends Controller
{
    public function index()
    {
        $equipos = Equipo::orderBy('id', 'desc')->get();
        $sexos = getJugadorSexos();
        $estados = getJugadorEstados();

        return view('admin.jugadores.index', compact('equipos', 'sexos', 'estados'));
    }

    /** Listando jugadores x equipo */
    public function listJugadores($ficha)
    {
        if ($ficha=='no-equipo') {
            $fichas = Jugador::orderBy('id', 'desc')->get();
        }else {
            $fichas = Jugador::where('equipo_id', $ficha)->orderBy('id', 'desc')->get();
        }

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $status_class = getStatusClass();

            $muestra_status = '<span class="badge badge-'.$status_class[$ficha->status].' w-100 p-1">'.strtoupper($ficha->status).'</span>';

            if ($ficha->foto) {
                $url_foto = Storage::url('jugador_fotos/'.$ficha->foto);
                $muestra_foto = '<img src="'.$url_foto.'" width="50" class="rounded">';
            }else {
                $muestra_foto = "-";
            }


            /** Calculando la edad */
            $fecha_nac = Carbon::parse($ficha->fecha_nac);
            $edad = $fecha_nac->diffInYears(Carbon::now());

            $json_response[] = array(
                "id" => $ficha->id,
                "foto" => $muestra_foto,
                "nombre" => $ficha->ape_paterno.' '.$ficha->ape_materno.', '.$ficha->nombres,
                "edad" => $edad,
                "telefono" => $ficha->telefono,
                "status" => $muestra_status,
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y'),
                "acciones" => '

                <div class="btn-group">
                    <a href="javascript:" class="mr-3" onclick="editarJugador('.$id.')" title="Editar Jugador">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javascript:" onclick="eliminarJugador('.$id.')" title="Eliminar Jugador">
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
        if(isset($data["foto_jugador"])) {
            $data_foto_jugador = SubirArchivo($data["foto_jugador"], 'jugador_fotos');
        }else {
            $data_foto_jugador = '';
        }

        $ficha =  Jugador::create($data);
        $ficha->equipo_id = $data['mdl_nj_equipo_id'];
        $ficha->foto = $data_foto_jugador;
        $ficha->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    /** Mostrando */
    public function show(Jugador $ficha)
    {
        $estados = getJugadorEstados();

        return response()->json([
            'ficha' => $ficha,
            'estados' => $estados
        ]);
    }

    /** Actualizando */
    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  Jugador::find($data['mdl_uj_jugador_id']);

        /* Subiendo foto */
        if(isset($data["mdl_uj_foto_jugador"])) {
            Storage::disk('public')->delete('arbitro_jugador_fotosfotos/'.$ficha->foto);
            $data_foto_jugador = SubirArchivo($data["mdl_uj_foto_jugador"], 'jugador_fotos');
        }else {
            $data_foto_jugador = $ficha->foto;
        }

        $ficha->ape_paterno = $data['mdl_uj_ape_paterno'];
        $ficha->ape_materno = $data['mdl_uj_ape_materno'];
        $ficha->nombres = $data['mdl_uj_nombres'];
        $ficha->fecha_nac = $data['mdl_uj_fecha_nac'];
        $ficha->telefono = $data['mdl_uj_telefono'];
        $ficha->sexo = $data['mdl_uj_sexo'];
        $ficha->status = $data['mdl_uj_status'];
        $ficha->foto = $data_foto_jugador;
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-jugador']);
        }

    }

}
