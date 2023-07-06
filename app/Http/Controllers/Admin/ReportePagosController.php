<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Illuminate\Http\Request;
use App\Models\EquipoPago;
use App\Models\Torneo;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;

class ReportePagosController extends Controller
{
    public function index()
    {
        $equipos = Equipo::all();
        $torneos = Torneo::all();

        return view('admin.reportes.index', compact('equipos', 'torneos'));
    }

    public function datatable(Request $request)
    {
        $columns = array(
            8 => 'created_at'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $fichas = EquipoPago::where('status','<>','denegada');

        $search = $request->input('search.value');

        if (trim($search) !== '' ) {

            $arr_search = explode("|",$search);

            if( isset($arr_search) ) {
                $equipo = $arr_search[0];
                $torneo = $arr_search[1];
                $status = $arr_search[2];

                if ( $equipo !== '' ) {
                    $fichas = $fichas->where('equipo_id', $equipo);
                }

                if ( $torneo !== '' ) {
                    $fichas = $fichas->where('torneo_id', $torneo);
                }

                if ( $status !== '' ) {
                    $fichas = $fichas->where('status', $status);
                }
            }

        }

        $totalData = $fichas->count();
        $totalFiltered = $totalData;

        $fichas = $fichas->offset($start)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

        $json_response = [];

        if(!empty($fichas)) {
            foreach($fichas as $ficha) {

                $json_response[] = array(
                    "id" => $ficha->id,
                    "equipo" => $ficha->equipos->nombre,
                    "torneo" => $ficha->torneos->nombre,
                    "costo" => $ficha->torneos->precio,
                    "adelanto" => $ficha->adelanto,
                    "saldo" => $ficha->saldo,
                    "status" => $ficha->status,
                    "usuario" => $ficha->usuarios->name,
                    "fecha_reg" => Date::parse($ficha->created_at)->format('d/m/Y H:i:s'),
                    "fecha_mod" => Date::parse($ficha->updated_at)->format('d/m/Y H:i:s')
                );
            }
        }

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data'            =>  $json_response
        ]);

    }


}
