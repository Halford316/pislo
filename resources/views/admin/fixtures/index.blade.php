@extends('adminlte::page')

@section('title', 'Fixtures')

@section('content_header')
<div class="card p-4">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8">
                <div class="d-flex flex-row text-white">
                    <div class="mr-4">
                        <i class="ico-fixture fa-4x"></i>
                    </div>
                    <div class="">
                        <h2 class="text-danger">
                            Fixtures
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="table-responsive p-4">
    <table class="table table-hover w-100 text-sm" id="tblFixtures">
        <thead>
            <tr class="bg-gray-dark">
                <th>ID</th>
                <th>TORNEO</th>
                <th>ESTADO</th>
                <th>REQUERIDOS</th>
                <th>EQUIPOS REGISTRADOS</th>
                <th>TABLA POSICIONES</th>
                <th>ACCIONES</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
    </table>

    <div id="tableInfo"></div>
    <div id="orderInfo"></div>
</div>

@include('admin.fixtures.mdl_tabla_posiciones')

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pislo.css') }}">
    <link rel="stylesheet" href="{{ asset('pislo-assets/icon_font/style.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/fixtures/adm_fixtures.js') }}" language="JavaScript"></script>

    <script>

    var flagUrl = '{{ URL::asset('') }}';

    $(document).ready(function() {

        /* Cargando los Fixtures  */
        var table = $('#tblFixtures').DataTable( {
            "responsive": true,
            bInfo: true,
            bSort: true,
            autoWidth: false,
            "processing": true,
            "searching" : true,
            "paging" : true,
            "iDisplayLength": 10,
            "info": false,
            ajax: {
                    "url" : flagUrl+"admin/fixtures/datatable",
                    "type" : "GET",
                    "data" : {'_token' : '{{ csrf_token() }}'}
                },
            "columnDefs": [
                {
                    className: "text-center", "targets": [0,2,3,4,5,6]
                }
            ],
            "aaSorting": [[ 0, "desc" ]],
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6] }
            ],

            "columns" : [
                { data: 'id' },
                { data: 'torneo' },
                { data: 'estado' },
                { data: 'requeridos' },
                { data: 'equipos_reg' },
                { data: 'tabla' },
                { data: 'acciones' }
            ],

            /*language: {
                    "sProcessing":     "Procesando...",
                    "sEmptyTable":     "No tiene registrado ningún alumno de pregrado",
                    "sLoadingRecords": "Cargando...",
                    "sSearch":         "Buscar:",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                }*/
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
