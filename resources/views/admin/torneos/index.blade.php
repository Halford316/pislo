@extends('adminlte::page')

@section('title', 'Torneos')

@section('content_header')
<div class="card p-4">

    <div class="card-header">
        <div class="row">
            <div class="col-sm-8">
                <div class="d-flex flex-row text-white">
                    <div class="mr-4">
                        <i class="ico-copa fa-4x"></i>
                    </div>
                    <div class="">
                        <h3 class="float-left text-danger">
                            Lista de torneos
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 pt-3">
                <div class="text-right">
                    <a href="{{ route('torneos.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr-1" aria-hidden="true"></i>
                        Nuevo torneo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="table-responsive p-4">
    <table class="table w-100 text-sm" id="tblTorneos">
        <thead>
            <tr class="th-datatable">
                <th>ID</th>
                <th>NOMBRE</th>
                <th>ESTADO</th>
                <th>EQUIPOS</th>
                <th>COSTO</th>
                <th>TxC</th>
                <th>TC</th>
                <th>TR</th>
                <th>EQUIPOS REGISTRADOS</th>
                <th>USUARIO</th>
                <th>FECHA REG</th>
                <th>ACCIONES</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
    </table>

    <div id="tableInfo"></div>
    <div id="orderInfo"></div>
</div>

@include('admin.torneos.mdl_equipos')

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pislo.css') }}">
    <link rel="stylesheet" href="{{ asset('pislo-assets/icon_font/style.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/torneos/torneos.js') }}" language="JavaScript"></script>
    <script>

    var flagUrl = '{{ URL::asset('') }}';

    $(document).ready(function() {

        /* Cargando los torneos  */
        var table = $('#tblTorneos').DataTable( {
            "responsive": true,
            bInfo: true,
            bSort: true,
            autoWidth: false,
            "processing": true,
            "searching" : true,
            "paging" : true,
            "iDisplayLength": 2,
            "info": false,
            oPaginate: false,
            ajax: {
                    "url" : flagUrl+"admin/torneos/datatable",
                    "type" : "POST",
                    "data" : {'_token' : '{{ csrf_token() }}'}
                },
            "aaSorting": [[ 6, "desc" ]],
            "aoColumnDefs": [
                { className: "text-center", "targets": [2,3,4,5,6,7] },
                { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6,7] }
            ],

            "columns" : [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'estado' },
                { data: 'equipos' },
                { data: 'costo' },
                { data: 'txc' },
                { data: 'tc' },
                { data: 'tr' },
                { data: 'equipos_reg' },
                { data: 'usuario' },
                { data: 'fecha_reg' },
                { data: 'acciones' }
            ],

            language:{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     " _MENU_  &nbsp; entradas por página",
                "sZeroRecords":    "¡No se obtuvieron resultados!",
                "sEmptyTable":     "¡No se obtuvieron resultados!",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de <span class='text-danger'>_TOTAL_</span> registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "&gt;",
                    "sPrevious": "&lt;"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

        } );




    } );

    </script>
@stop
