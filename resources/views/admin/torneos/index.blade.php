@extends('adminlte::page')

@section('title', 'Torneos')

@section('content_header')
    <h1 class="float-left text-danger">
        Lista de torneos
    </h1>


    <div class="float-right pb-5">
        <a href="{{ route('torneos.create') }}" class="btn btn-danger">
            <i class="fa fa-plus mr-1" aria-hidden="true"></i>
            Nuevo torneo
        </a>
    </div>
@stop

@section('content')

<div class="table-responsive">
    <table class="table table-striped table-hover w-100 text-sm" id="tblTorneos">
        <thead>
            <tr class="bg-gray-dark">
                <th>ID</th>
                <th>NOMBRE</th>
                <th>ESTADO</th>
                <th>EQUIPOS</th>
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

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>

    var flagUrl = '{{ URL::asset('') }}';

    $(document).ready(function() {

        /* Cargando los torneos  */
        var table = $('#tblTorneos').DataTable( {
            //"dom": 'rtip',
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
                    "url" : flagUrl+"admin/torneos/datatable",
                    "type" : "POST",
                    "data" : {'_token' : '{{ csrf_token() }}'}
                },
            "columnDefs": [
                {
                    className: "text-center", "targets": [1,2,3,6]
                }
            ],
            "aaSorting": [[ 6, "desc" ]],
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0,1,2,3,4,5,6,7] }
            ],

            "columns" : [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'estado' },
                { data: 'equipos' },
                { data: 'equipos_reg' },
                { data: 'usuario' },
                { data: 'fecha_reg' },
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
                "sLengthMenu":     "Mostrar _MENU_  &nbsp; registros",
                "sZeroRecords":    "¡No se obtuvieron resultados!",
                "sEmptyTable":     "¡No se obtuvieron resultados!",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
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
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
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
