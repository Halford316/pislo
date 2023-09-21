<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Torneo;
use App\Models\EquipoPago;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EquipoController extends Controller
{
    public function index()
    {
        $torneos = Torneo::orderBy('id', 'desc')->get();
        $sexos = getJugadorSexos();
        $estados = getJugadorEstados();

        return view('admin.equipos.index', compact('torneos', 'sexos', 'estados'));
    }

    public function datatable()
    {
        $fichas = Equipo::where('activo', '1')->orderBy('id', 'desc')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            if (!empty($ficha->foto)) {
                $url_foto = Storage::url('equipo_fotos/'.$ficha->foto);
                $muestra_foto = '<img src="'.$url_foto.'" width="50">';
            }else {
                $muestra_foto = '-';
            }

            $nro_jugadores = verTotalJugadoresXEquipo($id);

            $json_response[] = array(
                "id" => $id,
                "nombre" => $ficha->nombre,
                "foto" => $muestra_foto,
                "nro_jugadores" => $nro_jugadores,
                "jugadores_reg" => '
                        <button type="button" class="btn btn-secondary" onclick="verJugadores('.$id.')" title="Jugadores registrados">
                            <i class="fa fa-users mr-2"></i>
                            Ver listado
                        </button>
                ',
                "usuario" => ($ficha->user_id) ? $ficha->usuarios->name : '',
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y'),
                "acciones" => '

                <div class="btn-group">
                    <a href="javascript:" onclick="editarEquipo('.$id.')" title="Editar" class="mr-3">
                        <i class="ico-edicion fa-lg"></i>
                    </a>

                    <a href="javascript:" onclick="verPagos('.$id.')" title="Registrar pago" class="mr-3">
                        <i class="fa fa-credit-card"></i>
                    </a>

                    <a href="javascript:" onclick="eliminarEquipo('.$id.')" title="Eliminar">
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


    public function store(Request $request)
    {
        $data = request()->all();

        /* Subiendo foto */
        if(isset($data["foto_adjunto"])) {
            $data_foto_adjunto = SubirArchivo($data["foto_adjunto"], 'equipo_fotos');
        }else {
            $data_foto_adjunto = '';
        }

        $ficha =  Equipo::create([
                            'nombre' => $data['nombre'],
                            'foto' => $data_foto_adjunto,
                            'user_id' => Auth::user()->id
                    ]);

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    public function show(Equipo $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }

    /** Listando pagos registrados */
    public function listPagos($ficha, $torneo)
    {
        $fichas = EquipoPago::where('equipo_id', $ficha)->where('torneo_id', $torneo)->orderBy('id', 'desc')->get();

        $json_response = [];

        foreach($fichas as $ficha) {
            $id = $ficha->id;

            $status_class = getStatusClass();

            $muestra_status = '<span class="badge badge-'.$status_class[$ficha->status].' w-100 p-1">'.strtoupper($ficha->status).'</span>';

            $json_response[] = array(
                "equipo" => $ficha->equipos->nombre,
                "torneo" => $ficha->torneos->nombre,
                "pago" => 'S/ '.$ficha->registracion,
                "adelanto" => 'S/ '.$ficha->adelanto,
                "saldo" => 'S/ '.$ficha->saldo,
                "status" => $muestra_status,
                "usuario" => ($ficha->user_id) ? $ficha->usuarios->name : '',
                "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y'),
                "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y'),
                "acciones" => '

                <div class="btn-group">
                    <a href="javascript:" class="mr-3" onclick="editarPago('.$id.')" title="Editar pago">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javascript:" onclick="eliminarPago('.$id.')" title="Eliminar pago">
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

    /** Mostrando pago */
    public function showPago(EquipoPago $ficha)
    {
        return response()->json([
            'ficha' => $ficha
        ]);
    }

    public function showPrecioXTorneo($equipo, $torneo)
    {
        //$precio = $ficha->precio;
        $torneos = Torneo::find($torneo);
        $ficha = EquipoPago::where('equipo_id', $equipo)->where('torneo_id', $torneo)->orderBy('id', 'DESC')->get();

        return response()->json([
            'precio' => $torneos->precio,
            'ficha' => $ficha
        ]);
    }

    /** Guardando pago */
    public function storePago(Request $request)
    {
        $data = request()->all();

        $ficha = EquipoPago::create($data);

        $ficha->user_id = Auth::user()->id;

        if ($ficha->saldo == 0) {
            $ficha->status = 'cancelado';
        }

        $ficha->save();

        if ($ficha) {
            return response()->json(['success'=>'Data is successfully added']);
        }

    }

    /** Actualizando pago */
    public function updatePago(Request $request)
    {
        $data = request()->all();

        $ficha =  EquipoPago::find($data['mdl_up_pago_id']);

        $ficha->adelanto = $data['mdl_up_adelanto'];
        $ficha->saldo = $data['mdl_up_saldo'];

        if ($data['mdl_up_saldo'] == '0') {
            $ficha->status = 'cancelado';
        }else {
            $ficha->status = 'pendiente';
        }

        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-pago']);
        }

    }


    /** Eliminando pago */
    public function destroyPago($ficha)
    {
        $registro = EquipoPago::findOrFail($ficha);

        try {
            if ($registro->delete()) {

                return response()->json([
                    'status'=>'deleted'
                ]);
            }

        } catch (\Exception $e) {
            return "Error al eliminar el registro";
        }


    }

    /** Actualizando */
    public function update(Request $request)
    {
        $data = request()->all();

        $ficha =  Equipo::find($data['mdl_ue_equipo_id']);

        /* Subiendo foto */
        if(isset($data["mdl_ue_foto_adjunto"])) {
            Storage::disk('public')->delete('equipo_fotos/'.$ficha->foto);
            $data_foto_equipo = SubirArchivo($data["mdl_ue_foto_adjunto"], 'equipo_fotos');
        }else {
            $data_foto_equipo = $ficha->foto;
        }

        $ficha->nombre = $data['mdl_ue_nombre'];
        $ficha->foto = $data_foto_equipo;
        $ficha->save();

        if ($ficha) {
            return response()->json(['status'=>'updated-equipo']);
        }

    }

    /** Eliminando */
    public function destroy($id)
    {
        $ficha = Equipo::findOrFail($id);

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
